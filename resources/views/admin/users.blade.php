@extends('layouts.AdminMaster')

@section('title', 'User Management')

@push('styles')
<style>
    /* =========================================================
       CARDO-FER USER MANAGEMENT + USER ACTIVITY
       Dark Green / Gold Theme
    ========================================================= */
    .um-wrap {
        --um-bg: #0a1d15;
        --um-bg-deep: #07150f;
        --um-card: #10251b;
        --um-card-hover: #132d20;
        --um-green: #4CAF50;
        --um-green-light: #81C784;
        --um-cream: #F1F8E9;
        --um-sage: #a9c9ae;
        --um-gold: #FFD54F;
        --um-gold-deep: #d5893f;
        --um-line: rgba(241,248,233,0.10);
        --um-line-strong: rgba(129,199,132,0.18);
        --um-danger: #e2836b;
        color: var(--um-cream);
        font-family: inherit;
        background: #0a1d15;
        padding: 10px 0 30px;
    }

    .um-wrap * { box-sizing: border-box; }

    .um-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }

    .um-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .um-header-icon {
        width: 54px;
        height: 54px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: linear-gradient(135deg, var(--um-green), #2E7D32);
        border: 1px solid rgba(129,199,132,0.25);
        color: #fff;
        font-size: 21px;
        box-shadow: 0 12px 28px -14px rgba(76,175,80,0.65);
    }

    .um-header h2 {
        margin: 0;
        color: var(--um-cream);
        font-family: 'Fraunces', Georgia, serif;
        font-size: 28px;
        font-weight: 500;
    }

    .um-header p {
        margin: 5px 0 0;
        color: var(--um-sage);
        font-size: 13px;
    }

    .um-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .um-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 108px;
        padding: 18px 20px;
        background: linear-gradient(180deg, rgba(241,248,233,0.055), rgba(241,248,233,0.018));
        border: 1px solid var(--um-line-strong);
        border-radius: 17px;
        box-shadow: 0 12px 30px -22px rgba(0,0,0,0.7);
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .um-card:hover {
        transform: translateY(-3px);
        border-color: rgba(129,199,132,0.38);
        box-shadow: 0 18px 32px -20px rgba(0,0,0,0.70);
    }

    .um-card-icon {
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: var(--um-cream);
        font-size: 19px;
        transition: transform .18s ease;
    }

    .um-card.total .um-card-icon,
    .um-card.activity .um-card-icon {
        background: rgba(76,175,80,0.15);
        border: 1px solid rgba(76,175,80,0.25);
        color: var(--um-green-light);
    }

    .um-card.farmers .um-card-icon {
        background: rgba(129,199,132,0.12);
        border: 1px solid rgba(129,199,132,0.23);
        color: var(--um-green-light);
    }

    .um-card.admins .um-card-icon {
        background: rgba(255,213,79,0.11);
        border: 1px solid rgba(255,213,79,0.20);
        color: var(--um-gold);
    }

    .um-card-value {
        color: var(--um-cream);
        font-size: 25px;
        font-weight: 700;
        line-height: 1;
    }

    .um-card-label {
        margin-top: 6px;
        color: var(--um-sage);
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
    }

    .um-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
        padding: 14px;
        background: #10251b;
        border: 1px solid var(--um-line-strong);
        border-radius: 16px;
        box-shadow: 0 14px 28px -24px rgba(0,0,0,0.8);
    }

    .um-search-box {
        position: relative;
        flex: 1;
        min-width: 240px;
    }

    .um-search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--um-green-light);
        font-size: 13px;
        pointer-events: none;
    }

    .um-search-box input {
        width: 100%;
        padding: 11px 15px 11px 42px;
        border: 1px solid rgba(129,199,132,0.23);
        border-radius: 999px;
        background: #07150f;
        color: var(--um-cream);
        font-size: 13px;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .um-search-box input::placeholder { color: rgba(241,248,233,0.38); }

    .um-search-box input:focus {
        border-color: var(--um-gold);
        box-shadow: 0 0 0 3px rgba(255,213,79,0.12);
    }

    .um-filter-select {
        min-width: 145px;
        padding: 11px 15px;
        border: 1px solid rgba(129,199,132,0.23);
        border-radius: 999px;
        background: #07150f;
        color: var(--um-cream);
        font-size: 13px;
        outline: none;
    }

    .um-filter-select:focus { border-color: var(--um-gold); }
    .um-filter-select option { background: var(--um-bg-deep); color: var(--um-cream); }

    .um-result-count {
        color: var(--um-sage);
        font-size: 11.5px;
        white-space: nowrap;
    }

    .um-table-card {
        overflow: hidden;
        background: #10251b;
        border: 1px solid var(--um-line-strong);
        border-radius: 18px;
        box-shadow: 0 18px 40px -30px rgba(0,0,0,0.9);
    }

    .um-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        color: var(--um-cream);
    }

    .um-table thead th {
        padding: 14px 15px;
        background: rgba(241,248,233,0.035);
        border-bottom: 1px solid var(--um-line-strong);
        color: var(--um-green-light);
        font-size: 10.5px;
        letter-spacing: .7px;
        text-transform: uppercase;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .um-table tbody td {
        padding: 14px 15px;
        border-bottom: 1px solid var(--um-line);
        color: var(--um-sage);
        vertical-align: middle;
    }

    .um-table tbody tr:last-child td { border-bottom: none; }

    .um-table tbody tr { transition: background .18s ease; }
    .um-table tbody tr:hover { background: rgba(129,199,132,0.035); }

    .um-user-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .um-avatar,
    .um-view-avatar {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #1B5E20, #4CAF50);
        color: #fff;
        font-weight: 700;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.10);
    }

    .um-avatar { width: 39px; height: 39px; font-size: 13px; }
    .um-user-name { color: var(--um-cream); font-weight: 600; }

    .um-table tbody td:nth-child(3) { color: rgba(241,248,233,0.70); }

    .um-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .2px;
    }

    .um-badge.role-farmer,
    .um-badge.status-active,
    .um-badge.type-symptoms,
    .um-badge.status-completed {
        background: rgba(76,175,80,0.10);
        border: 1px solid rgba(76,175,80,0.20);
        color: var(--um-green-light);
    }

    .um-badge.role-admin,
    .um-badge.type-image,
    .um-badge.type-image_and_symptoms {
        background: rgba(255,213,79,0.10);
        border: 1px solid rgba(255,213,79,0.22);
        color: var(--um-gold);
    }

    .um-badge.status-inactive,
    .um-badge.status-failed {
        background: rgba(226,131,107,0.10);
        border: 1px solid rgba(226,131,107,0.20);
        color: var(--um-danger);
    }

    .um-badge.status-needs_confirmation {
        background: rgba(255,213,79,0.08);
        border: 1px solid rgba(255,213,79,0.18);
        color: #f2c94c;
    }

    .um-actions { display: flex; gap: 7px; }

    .um-icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        font-size: 12px;
        transition: transform .15s ease, background .15s ease, border-color .15s ease;
    }

    .um-icon-btn:hover { transform: translateY(-2px); }

    .um-icon-btn.view {
        color: var(--um-green-light);
        border: 1px solid rgba(129,199,132,0.22);
    }

    .um-icon-btn.view:hover { background: rgba(129,199,132,0.10); }

    .um-icon-btn.delete {
        color: var(--um-danger);
        border: 1px solid rgba(226,131,107,0.22);
    }

    .um-icon-btn.delete:hover { background: rgba(226,131,107,0.10); }

    .um-empty-row td { text-align: center; padding: 42px 15px; color: var(--um-sage); }

    .um-wrap .modal-content {
        overflow: hidden;
        background: #0a1d15;
        border: 1px solid var(--um-line-strong);
        border-radius: 17px;
        color: var(--um-cream);
        box-shadow: 0 25px 70px rgba(0,0,0,0.55);
    }

    .um-wrap .modal-header {
        padding: 17px 20px;
        background: linear-gradient(135deg, #0e3515, #1B5E20);
        border-bottom: 1px solid rgba(129,199,132,0.18);
    }

    .um-wrap .modal-title {
        color: var(--um-cream);
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 500;
        font-size: 18px;
    }

    .um-wrap .modal-title i { color: var(--um-gold); }

    .um-wrap .modal-header .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
    }

    .um-wrap .modal-body { padding: 22px; background: #0a1d15; }
    .um-wrap .modal-footer {
        padding: 14px 20px;
        background: #07150f;
        border-top: 1px solid var(--um-line);
    }

    .um-view-avatar {
        width: 72px;
        height: 72px;
        margin: 0 auto 14px;
        font-size: 25px;
        box-shadow: 0 14px 30px -18px rgba(76,175,80,0.65);
    }

    .um-view-name { color: var(--um-cream); font-weight: 700; margin-bottom: 4px; }
    .um-view-email { color: var(--um-sage); margin-bottom: 20px; }

    .um-view-list { list-style: none; padding: 0; margin: 0; }

    .um-view-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 11px 0;
        border-bottom: 1px solid var(--um-line);
        font-size: 12.5px;
    }

    .um-view-list li:last-child { border-bottom: none; }
    .um-view-list .label { color: var(--um-sage); font-weight: 600; }
    .um-view-list .value { color: var(--um-cream); font-weight: 600; text-align: right; }

    .um-activity-section {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--um-line-strong);
    }

    .um-activity-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }

    .um-activity-title {
        margin: 0;
        color: var(--um-cream);
        font-size: 15px;
        font-weight: 700;
    }

    .um-activity-count {
        font-size: 10.5px;
        color: var(--um-gold);
        border: 1px solid rgba(255,213,79,0.18);
        background: rgba(255,213,79,0.06);
        padding: 5px 9px;
        border-radius: 999px;
    }

    .um-activity-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 390px;
        overflow-y: auto;
        padding-right: 3px;
    }

    .um-activity-item {
        padding: 13px 14px;
        border: 1px solid var(--um-line);
        border-radius: 13px;
        background: rgba(241,248,233,0.025);
    }

    .um-activity-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }

    .um-activity-disease {
        margin: 0;
        color: var(--um-cream);
        font-size: 13px;
        font-weight: 700;
    }

    .um-activity-time {
        color: rgba(241,248,233,0.52);
        font-size: 10.5px;
        white-space: nowrap;
    }

    .um-activity-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 9px;
    }

    .um-activity-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 8px;
    }

    .um-activity-stat {
        padding: 9px 10px;
        border-radius: 10px;
        background: rgba(7,21,15,0.65);
        border: 1px solid rgba(129,199,132,0.08);
    }

    .um-activity-stat span {
        display: block;
        color: rgba(241,248,233,0.42);
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 3px;
    }

    .um-activity-stat strong {
        color: var(--um-cream);
        font-size: 12px;
    }

    .um-activity-symptoms {
        margin-top: 9px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .um-symptom-chip {
        padding: 5px 8px;
        border-radius: 999px;
        border: 1px solid rgba(129,199,132,0.14);
        color: var(--um-sage);
        background: rgba(129,199,132,0.04);
        font-size: 10px;
    }

    .um-activity-reason {
        margin: 10px 0 0;
        padding: 10px 11px;
        border-left: 2px solid var(--um-gold);
        background: rgba(255,213,79,0.035);
        color: rgba(241,248,233,0.68);
        font-size: 11px;
        line-height: 1.65;
    }

    .um-activity-empty {
        padding: 20px 14px;
        border: 1px dashed rgba(129,199,132,0.16);
        border-radius: 13px;
        text-align: center;
        color: rgba(241,248,233,0.48);
        font-size: 11.5px;
    }

    .um-analysis-details {
        margin-top: 10px;
        border-top: 1px solid var(--um-line);
        padding-top: 9px;
    }

    .um-analysis-details summary {
        cursor: pointer;
        color: var(--um-green-light);
        font-size: 10.5px;
        font-weight: 700;
        list-style: none;
    }

    .um-analysis-details summary::-webkit-details-marker { display: none; }

    .um-analysis-details summary i { color: var(--um-gold); margin-right: 5px; }

    .um-detail-block {
        margin-top: 9px;
        display: grid;
        gap: 8px;
    }

    .um-detail-box {
        padding: 10px 11px;
        border-radius: 10px;
        background: rgba(7,21,15,0.55);
        border: 1px solid var(--um-line);
    }

    .um-detail-box h6 {
        margin: 0 0 5px;
        color: var(--um-green-light);
        font-size: 9.5px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .um-detail-box p {
        margin: 0;
        color: rgba(241,248,233,0.66);
        font-size: 10.5px;
        line-height: 1.6;
        white-space: pre-line;
    }

    .um-image-preview {
        display: block;
        width: 100%;
        max-height: 230px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--um-line-strong);
    }

    .um-wrap .form-label {
        color: var(--um-green-light);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .um-wrap .form-control,
    .um-wrap .form-select {
        background: #07150f !important;
        border: 1px solid rgba(129,199,132,0.22);
        border-radius: 10px;
        color: var(--um-cream) !important;
        padding: 10px 12px;
    }

    .um-wrap .form-control::placeholder { color: rgba(241,248,233,0.36); }

    .um-wrap .form-control:focus,
    .um-wrap .form-select:focus {
        border-color: var(--um-gold);
        box-shadow: 0 0 0 3px rgba(255,213,79,0.12);
    }

    .um-wrap .form-select option { background: #0a1d15; color: var(--um-cream); }

    .um-btn-primary {
        border: none;
        background: linear-gradient(180deg, var(--um-gold), var(--um-gold-deep));
        color: #241708;
        font-weight: 700;
        border-radius: 999px;
        padding: 9px 21px;
    }

    .um-btn-primary:hover {
        color: #241708;
        box-shadow: 0 12px 24px -14px rgba(255,213,79,0.65);
    }

    .um-btn-outline {
        border: 1px solid var(--um-line-strong);
        color: var(--um-sage);
        background: transparent;
        font-weight: 600;
        border-radius: 999px;
        padding: 9px 21px;
    }

    .um-btn-outline:hover {
        color: var(--um-cream);
        border-color: var(--um-green-light);
    }

    .um-btn-danger {
        border: none;
        background: linear-gradient(180deg, #e2836b, #c9634a);
        color: #2b0d06;
        font-weight: 700;
        border-radius: 999px;
        padding: 9px 21px;
    }

    @media (max-width: 950px) {
        .um-summary { grid-template-columns: 1fr; }
    }

    @media (max-width: 650px) {
        .um-header { align-items: flex-start; }
        .um-toolbar { flex-direction: column; align-items: stretch; }
        .um-filter-select { width: 100%; }
        .um-result-count { align-self: flex-start; }
        .um-table-card { overflow-x: auto; }
        .um-table { min-width: 760px; }
        .um-activity-grid { grid-template-columns: 1fr; }
        .um-activity-top { flex-direction: column; }
        .um-activity-time { white-space: normal; }
    }
</style>
@endpush

@section('content')
<div class="um-wrap">

    <div class="um-header">
        <div class="um-header-left">
            <div class="um-header-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h2>User Management</h2>
                <p>Manage registered farmers and administrators, including their analysis activity.</p>
            </div>
        </div>
    </div>

    <div class="um-summary">
        <div class="um-card total">
            <div class="um-card-icon"><i class="fa-solid fa-user-group"></i></div>
            <div>
                <div class="um-card-value">{{ $users->count() }}</div>
                <div class="um-card-label">Total Users</div>
            </div>
        </div>

        <div class="um-card farmers">
            <div class="um-card-icon"><i class="fa-solid fa-seedling"></i></div>
            <div>
                <div class="um-card-value">{{ $users->where('role', 'user')->count() }}</div>
                <div class="um-card-label">Farmers</div>
            </div>
        </div>

        <div class="um-card admins">
            <div class="um-card-icon"><i class="fa-solid fa-user-shield"></i></div>
            <div>
                <div class="um-card-value">{{ $users->where('role', 'admin')->count() }}</div>
                <div class="um-card-label">Administrators</div>
            </div>
        </div>
    </div>

    <div class="um-toolbar">
        <div class="um-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="umSearchInput" placeholder="Search by name or email..." aria-label="Search users">
        </div>

        <select id="umRoleFilter" class="um-filter-select" aria-label="Filter users by role">
            <option value="all">All Roles</option>
            <option value="user">Farmer</option>
            <option value="admin">Admin</option>
        </select>

        <span class="um-result-count" id="umResultCount"></span>
    </div>

    <div class="um-table-card">
        <div class="table-responsive">
            <table class="um-table" id="umUsersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $index => $user)
                        <tr class="um-user-row"
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ strtolower($user->role) }}">

                            <td>{{ $index + 1 }}</td>

                            <td>
                                <div class="um-user-cell">
                                    <div class="um-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="um-user-name">{{ $user->name }}</span>
                                </div>
                            </td>

                            <td>{{ $user->email }}</td>

                            <td>
                                @if (strtolower($user->role) === 'admin')
                                    <span class="um-badge role-admin">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        Admin
                                    </span>
                                @else
                                    <span class="um-badge role-farmer">
                                        <i class="fa-solid fa-leaf"></i>
                                        Farmer
                                    </span>
                                @endif
                            </td>

                            <td>{{ optional($user->created_at)->format('d M Y') }}</td>

                            <td>
                                @if (isset($user->status) && strtolower($user->status) === 'inactive')
                                    <span class="um-badge status-inactive">
                                        <i class="fa-solid fa-circle"></i>
                                        Inactive
                                    </span>
                                @else
                                    <span class="um-badge status-active">
                                        <i class="fa-solid fa-circle"></i>
                                        Active
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="um-actions">
                                    <button type="button"
                                            class="um-icon-btn view"
                                            title="View User & Activity"
                                            data-bs-toggle="modal"
                                            data-bs-target="#umViewModal{{ $user->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="um-icon-btn delete"
                                            title="Delete User"
                                            data-bs-toggle="modal"
                                            data-bs-target="#umDeleteModal{{ $user->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="um-empty-row">
                                <i class="fa-solid fa-users-slash mb-2"></i>
                                <div>No registered users found.</div>
                            </td>
                        </tr>
                    @endforelse

                    <tr class="um-empty-row" id="umNoResultsRow" style="display:none;">
                        <td colspan="7">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <div>No users match your search.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($users as $user)
        @php
            $activities = $userActivities->get($user->id, collect());
        @endphp

        {{-- ===================== VIEW USER + ACTIVITY ===================== --}}
        <div class="modal fade" id="umViewModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-chart-line me-2"></i>
                            User Activity Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="text-center">
                            <div class="um-view-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <h5 class="um-view-name">{{ $user->name }}</h5>
                            <p class="um-view-email">{{ $user->email }}</p>
                        </div>

                        <ul class="um-view-list">
                            <li>
                                <span class="label">User ID</span>
                                <span class="value">#{{ $user->id }}</span>
                            </li>

                            <li>
                                <span class="label">Role</span>
                                <span class="value">
                                    @if (strtolower($user->role) === 'admin')
                                        <span class="um-badge role-admin">Admin</span>
                                    @else
                                        <span class="um-badge role-farmer">Farmer</span>
                                    @endif
                                </span>
                            </li>

                            <li>
                                <span class="label">Registered On</span>
                                <span class="value">{{ optional($user->created_at)->format('d M Y, h:i A') }}</span>
                            </li>

                            <li>
                                <span class="label">Account Status</span>
                                <span class="value">
                                    @if (isset($user->status) && strtolower($user->status) === 'inactive')
                                        <span class="um-badge status-inactive">Inactive</span>
                                    @else
                                        <span class="um-badge status-active">Active</span>
                                    @endif
                                </span>
                            </li>
                        </ul>

                        {{-- ================= ACTIVITY ================= --}}
                        <div class="um-activity-section">
                            <div class="um-activity-head">
                                <h6 class="um-activity-title">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i>
                                    Analysis Activity
                                </h6>
                                <span class="um-activity-count">
                                    {{ $activities->count() }} {{ $activities->count() === 1 ? 'Analysis' : 'Analyses' }}
                                </span>
                            </div>

                            @if ($activities->isNotEmpty())
                                <div class="um-activity-list">
                                    @foreach ($activities as $activity)
                                        @php
                                            $typeLabel = match ($activity->analysis_type) {
                                                'image_and_symptoms' => 'Image + Symptoms',
                                                'image' => 'Image Analysis',
                                                'symptoms' => 'Symptom Analysis',
                                                default => ucwords(str_replace('_', ' ', $activity->analysis_type ?? 'Analysis')),
                                            };

                                            $confidence = $activity->ai_confidence_score !== null
                                                ? round((float) $activity->ai_confidence_score) . '%'
                                                : ($activity->ai_confidence ?: 'Not available');

                                            $dbMatch = $activity->database_match_percentage !== null
                                                ? round((float) $activity->database_match_percentage) . '%'
                                                : ($activity->database_match_found ? 'Matched' : 'No match');
                                        @endphp

                                        <div class="um-activity-item">
                                            <div class="um-activity-top">
                                                <div>
                                                    <p class="um-activity-disease">
                                                        {{ $activity->disease_name ?: 'Disease not recorded' }}
                                                    </p>
                                                </div>
                                                <div class="um-activity-time">
                                                    {{ optional($activity->analyzed_at ?? $activity->created_at)->format('d M Y, h:i A') }}
                                                </div>
                                            </div>

                                            <div class="um-activity-meta">
                                                <span class="um-badge {{ $activity->analysis_type === 'symptoms' ? 'type-symptoms' : ($activity->analysis_type === 'image' ? 'type-image' : 'type-image_and_symptoms') }}">
                                                    <i class="fa-solid fa-microscope"></i>
                                                    {{ $typeLabel }}
                                                </span>

                                                <span class="um-badge {{ $activity->status === 'completed' ? 'status-completed' : ($activity->status === 'failed' ? 'status-failed' : 'status-needs_confirmation') }}">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    {{ ucwords(str_replace('_', ' ', $activity->status ?? 'Unknown')) }}
                                                </span>
                                            </div>

                                            <div class="um-activity-grid">
                                                <div class="um-activity-stat">
                                                    <span>AI Confidence</span>
                                                    <strong>{{ $confidence }}</strong>
                                                </div>

                                                <div class="um-activity-stat">
                                                    <span>Database Match</span>
                                                    <strong>{{ $dbMatch }}</strong>
                                                </div>
                                            </div>

                                            @if ($activity->symptoms && $activity->symptoms->isNotEmpty())
                                                <div class="um-activity-symptoms">
                                                    @foreach ($activity->symptoms as $loggedSymptom)
                                                        <span class="um-symptom-chip">
                                                            {{ $loggedSymptom->symptom_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if ($activity->ai_reason)
                                                <div class="um-activity-reason">
                                                    {{ $activity->ai_reason }}
                                                </div>
                                            @endif

                                            <details class="um-analysis-details">
                                                <summary>
                                                    <i class="fa-solid fa-chevron-down"></i>
                                                    View analysis details
                                                </summary>

                                                <div class="um-detail-block">
                                                    @if ($activity->field_area !== null || $activity->field_plant_count !== null)
                                                        <div class="um-detail-box">
                                                            <h6>Field Details</h6>
                                                            <p>
                                                                Area: {{ $activity->field_area ?? 'Not recorded' }} {{ $activity->field_area_unit ?? '' }}
                                                                @if ($activity->field_plant_count !== null)
                                                                    | Plants: {{ $activity->field_plant_count }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif

                                                    @if ($activity->visual_summary)
                                                        <div class="um-detail-box">
                                                            <h6>Visual Summary</h6>
                                                            <p>{{ $activity->visual_summary }}</p>
                                                        </div>
                                                    @endif

                                                    @if ($activity->causes)
                                                        <div class="um-detail-box">
                                                            <h6>Possible Causes</h6>
                                                            <p>{{ $activity->causes }}</p>
                                                        </div>
                                                    @endif

                                                    @if ($activity->prevention)
                                                        <div class="um-detail-box">
                                                            <h6>Prevention</h6>
                                                            <p>{{ $activity->prevention }}</p>
                                                        </div>
                                                    @endif

                                                    @if ($activity->fertilizer)
                                                        <div class="um-detail-box">
                                                            <h6>Fertilizer Recommendation</h6>
                                                            <p>{{ $activity->fertilizer }}</p>
                                                        </div>
                                                    @endif

                                                    @if ($activity->image_path)
                                                        <div class="um-detail-box">
                                                            <h6>Uploaded Plant Image</h6>
                                                            <img src="{{ \Illuminate\Support\Facades\Storage::url($activity->image_path) }}"
                                                                 alt="Uploaded plant image"
                                                                 class="um-image-preview">
                                                        </div>
                                                    @endif

                                                    @if ($activity->database_disease_name || $activity->database_match_percentage !== null)
                                                        <div class="um-detail-box">
                                                            <h6>Database Comparison</h6>
                                                            <p>
                                                                Database disease: {{ $activity->database_disease_name ?: 'No registered match' }}
                                                                @if ($activity->database_match_percentage !== null)
                                                                    | Match: {{ round((float) $activity->database_match_percentage) }}%
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </details>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="um-activity-empty">
                                    <i class="fa-solid fa-clock-rotate-left mb-2"></i>
                                    <div>No analysis activity has been recorded for this user yet.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="um-btn-outline" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== DELETE MODAL ===================== --}}
        <div class="modal fade" id="umDeleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST"
                          action="{{ route('user_delete', $user->id) }}"
                          onsubmit="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')

                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                Delete User
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p style="margin:0;color:var(--um-sage);line-height:1.7;">
                                Are you sure you want to delete
                                <strong style="color:var(--um-cream);">{{ $user->name }}</strong>
                                (<span style="color:var(--um-green-light);">{{ $user->email }}</span>)?
                                <br>
                                This action cannot be undone.
                            </p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="um-btn-outline" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="um-btn-danger">Delete User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
(function () {
    const searchInput = document.getElementById('umSearchInput');
    const roleFilter = document.getElementById('umRoleFilter');
    const rows = document.querySelectorAll('#umUsersTable .um-user-row');
    const noResultsRow = document.getElementById('umNoResultsRow');
    const resultCount = document.getElementById('umResultCount');

    function applyFilters() {
        const query = searchInput?.value.trim().toLowerCase() || '';
        const role = roleFilter?.value || 'all';
        let visibleCount = 0;

        rows.forEach(function (row) {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const userRole = row.dataset.role || '';

            const matchesSearch = name.includes(query) || email.includes(query);
            const matchesRole = role === 'all' || userRole === role;
            const visible = matchesSearch && matchesRole;

            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (noResultsRow) {
            noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
        }

        if (resultCount) {
            resultCount.textContent = rows.length
                ? `${visibleCount} of ${rows.length} users`
                : '';
        }
    }

    searchInput?.addEventListener('input', applyFilters);
    roleFilter?.addEventListener('change', applyFilters);
    applyFilters();

    document.querySelectorAll('.um-card').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            const icon = card.querySelector('.um-card-icon');
            if (icon) icon.style.transform = 'scale(1.06)';
        });

        card.addEventListener('mouseleave', function () {
            const icon = card.querySelector('.um-card-icon');
            if (icon) icon.style.transform = '';
        });
    });
})();
</script>
@endpush
