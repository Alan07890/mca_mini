<?php

namespace App\Console\Commands;

use App\Mail\FertilizerReminderMail;
use App\Models\FertilizerApplication;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendFertilizerReminders extends Command
{
    protected $signature = 'fertilizer:send-reminders';

    protected $description =
        'Send advance and due-date fertilizer reminder emails';

    public function handle(): int
    {
        $timezone = 'Asia/Kolkata';

        $today = Carbon::now($timezone)->startOfDay();
        $todayString = $today->toDateString();

        // Find applications that still need at least one reminder.
        $applications = FertilizerApplication::with('fertilizer')
            ->whereNotNull('next_application_date')
            ->whereDate('next_application_date', '>=', $todayString)
            ->where(function ($query) {
                $query->whereNull('advance_reminder_sent_at')
                    ->orWhereNull('reminder_sent_at');
            })
            ->get();

        if ($applications->isEmpty()) {
            $this->info('No fertilizer reminders need to be sent today.');

            return self::SUCCESS;
        }

        foreach ($applications as $application) {
            $user = User::find($application->user_id);

            if (!$user || !$user->email) {
                $this->warn(
                    "No registered email found for application #{$application->id}."
                );

                continue;
            }

            $appliedDate = Carbon::parse(
                $application->applied_date,
                $timezone
            )->startOfDay();

            $dueDate = Carbon::parse(
                $application->next_application_date,
                $timezone
            )->startOfDay();

            $totalDays = max(
                0,
                (int) $appliedDate->diffInDays($dueDate)
            );

            $reminderType = null;
            $daysUntilDue = 0;
            $sentColumn = null;

            /*
             * Priority 1: Due-date reminder.
             * Send only on the scheduled application date.
             */
            if (
                $dueDate->toDateString() === $todayString
                && !$application->reminder_sent_at
            ) {
                $reminderType = 'due';
                $sentColumn = 'reminder_sent_at';
            }

            /*
             * Priority 2: One advance reminder.
             * Skip advance reminders when the interval is 2 days or less.
             */
            elseif (
                $dueDate->greaterThan($today)
                && $totalDays > 2
                && !$application->advance_reminder_sent_at
            ) {
                $advanceDays = (int) ceil($totalDays / 2);

                $advanceDate = $appliedDate->copy()
                    ->addDays($advanceDays);

                if ($advanceDate->toDateString() === $todayString) {
                    $reminderType = 'advance';

                    $daysUntilDue = (int) $today->diffInDays($dueDate);

                    $sentColumn = 'advance_reminder_sent_at';
                }
            }

            // This application does not need an email today.
            if ($reminderType === null) {
                continue;
            }

            try {
                Mail::to($user->email)->send(
                    new FertilizerReminderMail(
                        $application,
                        $user->name ?? 'Farmer',
                        $reminderType,
                        $daysUntilDue
                    )
                );

                // Record the reminder only after the email succeeds.
                $application->forceFill([
                    $sentColumn => Carbon::now($timezone),
                ])->save();

                $this->info(
                    ucfirst($reminderType)
                    . " reminder sent for application #{$application->id}."
                );

            } catch (Throwable $e) {
                report($e);

                $this->error(
                    "Email failed for application #{$application->id}."
                );
            }
        }

        $this->info('Fertilizer reminder check completed.');

        return self::SUCCESS;
    }
}