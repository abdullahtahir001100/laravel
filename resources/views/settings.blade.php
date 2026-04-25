<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Settings</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<style>
		:root {
			--brand: #0f62fe;
			--brand-soft: #eaf2ff;
			--line: #d8e2f0;
			--text: #0f172a;
			--muted: #5d6b82;
			--bg: #f2f7ff;
			--card: #ffffff;
			--ok: #0e9f6e;
			--warn: #d97706;
			--danger: #dc2626;
		}

		body {
			font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
			background:
				radial-gradient(900px 580px at 5% -15%, #d7ecff 0%, transparent 62%),
				radial-gradient(880px 500px at 95% -10%, #dff8e8 0%, transparent 60%),
				var(--bg);
			color: var(--text);
		}

		.panel {
			border: 1px solid var(--line);
			border-radius: 5px;
			background: var(--card);
		}

		.radius-5 {
			border-radius: 5px !important;
		}

		.left-nav-item.active {
			background: var(--brand-soft);
			border-color: #bfd5ff;
			color: #114fc7;
			font-weight: 700;
		}

		.form-input,
		.form-select,
		.form-textarea {
			width: 100%;
			border: 1px solid var(--line);
			border-radius: 5px;
			padding: 10px 12px;
			font-size: 14px;
			outline: none;
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
			background: #fff;
		}

		.form-input:focus,
		.form-select:focus,
		const SETTINGS_KEYS = Object.keys(DEFAULT_VALUES).filter((key) => !PROFILE_KEYS.includes(key));
		.form-textarea:focus {
			border-color: #7db1ff;
			box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.12);
		}

		.form-error {
			border-color: #ef4444 !important;
			box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12) !important;
		}

		.toggle {
			appearance: none;
			width: 44px;
			height: 24px;
			border-radius: 999px;
			border: 1px solid #becde2;
			background: #c7d2e3;
			position: relative;
			cursor: pointer;
			transition: all 0.2s ease;
		}

		.toggle::before {
			content: "";
			position: absolute;
			top: 2px;
			left: 2px;
			width: 18px;
			height: 18px;
			border-radius: 999px;
			background: #fff;
			transition: all 0.2s ease;
		}

		.toggle:checked {
			background: var(--brand);
			border-color: var(--brand);
		}

		.toggle:checked::before {
			left: 22px;
		}

		.pill {
			border: 1px solid var(--line);
			background: #f8fbff;
			border-radius: 999px;
			padding: 5px 10px;
			font-size: 11px;
			color: #3b4a63;
			font-weight: 600;
		}
	</style>
</head>

<body class="min-h-screen overflow-hidden">
	<x-dashboard-header />

	<div class="flex pt-16 h-screen overflow-hidden">
		<x-dashboard-sidebar />

	<div class="flex-1 overflow-y-auto">
	<div class="max-w-[1700px] mx-auto px-4 md:px-8 py-6 md:py-8">
		<header class="panel p-4 md:p-5 mb-6">
			<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
				<div>
					<h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Settings</h1>
					<p class="text-sm text-slate-500 mt-1">Users can manage their preferences and control their account settings.</p>
					<div class="flex flex-wrap gap-2 mt-3">
						<span class="pill">Profile</span>
						<span class="pill">Privacy</span>
						<span class="pill">Security</span>
						<span class="pill">Activity</span>
						<span class="pill">Data</span>
						<span class="pill">Notifications</span>
					</div>
				</div>

				<div class="flex flex-wrap gap-2">
					<button id="discardAllBtn" type="button" class="px-4 py-2 border border-slate-300 bg-white hover:bg-slate-50 text-sm font-semibold radius-5">Discard All</button>
					<button id="saveAllBtn" type="button" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold radius-5">Save All Changes</button>
					<a href="/user-profile" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-sm font-semibold radius-5 border border-slate-200">Back To Profile</a>
				</div>
			</div>
		</header>

		<div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
			<aside class="xl:col-span-3">
				<div class="panel p-4 sticky top-6">
					<div class="mb-3">
						<label for="settingsSearch" class="text-xs font-semibold text-slate-500">Search Settings</label>
						<input id="settingsSearch" type="text" class="form-input mt-1" placeholder="Search: password, privacy, phone...">
					</div>

					<div class="md:hidden mb-3">
						<label for="mobileSectionSelect" class="text-xs font-semibold text-slate-500">Jump To Section</label>
						<select id="mobileSectionSelect" class="form-select mt-1"></select>
					</div>

					<nav id="leftNav" class="flex flex-col gap-2"></nav>

					<div class="mt-4 p-3 border border-slate-200 radius-5 bg-slate-50">
						<p class="text-xs text-slate-500 font-semibold">Change Status</p>
						<div class="flex items-center justify-between mt-1">
							<span id="changeCounter" class="text-sm font-bold text-slate-700">0 unsaved changes</span>
							<span id="syncBadge" class="text-[11px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Synced</span>
						</div>
					</div>
				</div>
			</aside>

			<main class="xl:col-span-9">
				<section id="sectionHost" class="space-y-6"></section>
			</main>
		</div>
	</div>
	</div>
	</div>

	<div id="toast" class="fixed right-4 bottom-4 z-50 hidden px-4 py-2.5 text-sm font-semibold text-white radius-5"></div>

	<template id="sectionTemplate">
		<article class="panel section-panel p-4 md:p-6" data-section="">
			<div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
				<div>
					<h2 class="text-xl font-extrabold section-title"></h2>
					<p class="text-sm text-slate-500 section-description"></p>
				</div>
				<button type="button" class="save-section-btn px-3 py-2 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 radius-5">Save Section</button>
			</div>
			<div class="mt-4 section-groups space-y-4"></div>
		</article>
	</template>

	<template id="groupTemplate">
		<div class="group-box border border-slate-200 radius-5 p-4 bg-slate-50/60">
			<h3 class="text-sm font-bold group-title"></h3>
			<p class="text-xs text-slate-500 mt-1 group-description"></p>
			<div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 group-fields"></div>
		</div>
	</template>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
	<script>
		const SECTION_ICONS = {
			profile: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
			about: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01"></path></svg>',
			privacy: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.657 1.343-3 3-3h1V6a4 4 0 00-8 0v2h1c1.657 0 3 1.343 3 3z"></path><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 11h14v10H5z"></path></svg>',
			security: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 4v5c0 5-3.5 8.5-7 9-3.5-.5-7-4-7-9V7l7-4z"></path></svg>',
			account: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 013.35 0 1.724 1.724 0 002.573 1.066 1.724 1.724 0 012.37 2.37 1.724 1.724 0 001.065 2.572 1.724 1.724 0 010 3.35 1.724 1.724 0 00-1.066 2.573 1.724 1.724 0 01-2.37 2.37 1.724 1.724 0 00-2.572 1.065 1.724 1.724 0 01-3.35 0 1.724 1.724 0 00-2.573-1.066 1.724 1.724 0 01-2.37-2.37 1.724 1.724 0 00-1.065-2.572 1.724 1.724 0 010-3.35 1.724 1.724 0 001.066-2.573 1.724 1.724 0 012.37-2.37 1.724 1.724 0 002.572-1.065z"></path></svg>',
			notifications: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1"></path></svg>',
			activity: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3 8 4-16 3 8h4"></path></svg>',
			data: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h10"></path></svg>'
		};

		const SETTINGS_SCHEMA = [
			{
				id: 'profile',
				title: 'Profile Details',
				description: 'Edit name, contact, and identity information shown in your account.',
				groups: [
					{
						title: 'Identity',
						description: 'Core personal details used across your profile.',
						fields: [
							{ key: 'fullName', type: 'text', label: 'Full Name', required: true },
							{ key: 'displayName', type: 'text', label: 'Display Name', required: true },
							{ key: 'username', type: 'text', label: 'Username', required: true },
							{ key: 'pronouns', type: 'text', label: 'Pronouns' }
						]
					},
					{
						title: 'Contact',
						description: 'Contact channels and location.',
						fields: [
							{ key: 'email', type: 'email', label: 'Email Address', required: true },
							{ key: 'phone', type: 'tel', label: 'Phone Number', required: true },
							{ key: 'country', type: 'text', label: 'Country' },
							{ key: 'city', type: 'text', label: 'City' }
						]
					}
				]
			},
			{
				id: 'about',
				title: 'About And Bio',
				description: 'Public profile story and professional highlights.',
				groups: [
					{
						title: 'Profile Story',
						description: 'What users see first on your profile.',
						fields: [
							{ key: 'headline', type: 'text', label: 'Headline' },
							{ key: 'about', type: 'textarea', label: 'About', rows: 4 },
							{ key: 'bio', type: 'textarea', label: 'Bio', rows: 4 },
							{ key: 'website', type: 'text', label: 'Website URL' }
						]
					}
				]
			},
			{
				id: 'privacy',
				title: 'Privacy Controls',
				description: 'Audience, visibility, and profile discoverability options.',
				groups: [
					{
						title: 'Audience',
						description: 'Who can view your content and contact details.',
						fields: [
							{ key: 'privateAccount', type: 'switch', label: 'Private Account' },
							{ key: 'showEmail', type: 'switch', label: 'Show Email Publicly' },
							{ key: 'showPhone', type: 'switch', label: 'Show Phone Publicly' },
							{ key: 'showOnlineStatus', type: 'switch', label: 'Show Online Status' }
						]
					},
					{
						title: 'Discoverability',
						description: 'How your profile can be found.',
						fields: [
							{ key: 'searchEngineIndexing', type: 'switch', label: 'Allow Search Engine Indexing' },
							{ key: 'profileSuggestions', type: 'switch', label: 'Show In Suggested Profiles' },
							{ key: 'tagReview', type: 'switch', label: 'Require Tag Approval' },
							{ key: 'mentionReview', type: 'switch', label: 'Require Mention Approval' }
						]
					}
				]
			},
			{
				id: 'security',
				title: 'Security And Login',
				description: 'Protect account access and manage sessions securely.',
				groups: [
					{
						title: 'Password And Recovery',
						description: 'Update credentials and recovery channels.',
						fields: [
							{ key: 'currentPassword', type: 'password', label: 'Current Password' },
							{ key: 'newPassword', type: 'password', label: 'New Password' },
							{ key: 'confirmPassword', type: 'password', label: 'Confirm Password' },
							{ key: 'recoveryEmail', type: 'email', label: 'Recovery Email' }
						]
					},
					{
						title: 'Protection',
						description: 'Second layer and session-related controls.',
						fields: [
							{ key: 'twoFactor', type: 'switch', label: 'Enable Two-Factor Authentication' },
							{ key: 'loginAlerts', type: 'switch', label: 'Login Alerts On New Device' },
							{ key: 'trustedDevicesOnly', type: 'switch', label: 'Allow Trusted Devices Only' },
							{ key: 'autoSessionTimeout', type: 'switch', label: 'Auto Session Timeout' }
						]
					}
				]
			},
			{
				id: 'account',
				title: 'Account Preferences',
				description: 'Language, appearance, and account mode preferences.',
				groups: [
					{
						title: 'General Preferences',
						description: 'Preferences that affect general account behavior.',
						fields: [
							{ key: 'accountType', type: 'select', label: 'Account Type', options: ['Creator', 'Business', 'Personal'] },
							{ key: 'language', type: 'select', label: 'Language', options: ['English', 'Urdu'] },
							{ key: 'timezone', type: 'select', label: 'Timezone', options: ['Asia/Karachi', 'UTC', 'Asia/Dubai'] },
							{ key: 'contentLanguage', type: 'select', label: 'Preferred Content Language', options: ['English', 'Urdu', 'Both'] }
						]
					},
					{
						title: 'Accessibility',
						description: 'Display and usability improvements.',
						fields: [
							{ key: 'reducedMotion', type: 'switch', label: 'Reduce Motion Effects' },
							{ key: 'highContrast', type: 'switch', label: 'High Contrast Mode' },
							{ key: 'largerText', type: 'switch', label: 'Larger Text Preference' },
							{ key: 'keyboardNavigation', type: 'switch', label: 'Keyboard Navigation Hints' }
						]
					}
				]
			},
			{
				id: 'notifications',
				title: 'Notifications',
				description: 'Control push, email, and in-app notification preferences.',
				groups: [
					{
						title: 'In-App Notifications',
						description: 'Real-time alerts inside the app.',
						fields: [
							{ key: 'notifyLikes', type: 'switch', label: 'Likes' },
							{ key: 'notifyComments', type: 'switch', label: 'Comments' },
							{ key: 'notifyMentions', type: 'switch', label: 'Mentions' },
							{ key: 'notifyFollows', type: 'switch', label: 'Follow Requests' }
						]
					},
					{
						title: 'Email And Push',
						description: 'External communication preferences.',
						fields: [
							{ key: 'emailDigest', type: 'switch', label: 'Weekly Email Digest' },
							{ key: 'productUpdates', type: 'switch', label: 'Product Updates' },
							{ key: 'marketingEmails', type: 'switch', label: 'Marketing Emails' },
							{ key: 'pushNotifications', type: 'switch', label: 'Push Notifications' }
						]
					}
				]
			},
			{
				id: 'activity',
				title: 'Activity Preferences',
				description: 'Control history, interaction tracking, and personalization.',
				groups: [
					{
						title: 'Feed And Interaction',
						description: 'Behavior and recommendations based on your actions.',
						fields: [
							{ key: 'saveWatchHistory', type: 'switch', label: 'Save Watched Posts History' },
							{ key: 'saveLikeHistory', type: 'switch', label: 'Save Liked Posts History' },
							{ key: 'personalizedRecommendations', type: 'switch', label: 'Personalized Recommendations' },
							{ key: 'autoplayVideos', type: 'switch', label: 'Autoplay Videos' }
						]
					},
					{
						title: 'History Actions',
						description: 'Manage and clear activity stores.',
						fields: [
							{ key: 'likedPostsVisible', type: 'switch', label: 'Show Liked Posts In Profile' },
							{ key: 'watchedPostsVisible', type: 'switch', label: 'Show Watched Posts In Profile' },
							{ key: 'activityRetention', type: 'select', label: 'Activity Retention', options: ['30 Days', '90 Days', '1 Year'] },
							{ key: 'historySync', type: 'switch', label: 'Sync History Across Devices' }
						]
					}
				]
			},
			{
				id: 'data',
				title: 'Data And Account Lifecycle',
				description: 'Export data, backup controls, and account lifecycle actions.',
				groups: [
					{
						title: 'Data',
						description: 'Export and backup preferences.',
						fields: [
							{ key: 'dataExportFormat', type: 'select', label: 'Export Format', options: ['ZIP', 'JSON', 'CSV'] },
							{ key: 'includeMediaInExport', type: 'switch', label: 'Include Media In Export' },
							{ key: 'backupFrequency', type: 'select', label: 'Backup Frequency', options: ['Daily', 'Weekly', 'Monthly'] },
							{ key: 'backupEmail', type: 'email', label: 'Backup Notification Email' }
						]
					},
					{
						title: 'Lifecycle',
						description: 'Sensitive account actions for production workflows.',
						fields: [
							{ key: 'deactivationWindow', type: 'select', label: 'Deactivation Window', options: ['7 Days', '14 Days', '30 Days'] },
							{ key: 'accountDeletionProtection', type: 'switch', label: 'Require Password Before Deletion' },
							{ key: 'legalConsent', type: 'switch', label: 'I Confirm Policy And Terms Updates' },
							{ key: 'gdprMode', type: 'switch', label: 'Strict Data Privacy Mode' }
						]
					}
				]
			}
		];

		const DEFAULT_VALUES = {
			fullName: 'InkByHand Calligraphy',
			displayName: 'InkByHand Studio',
			username: 'inkbyhand',
			pronouns: 'he/him',
			email: 'ink@studio.com',
			phone: '+92 300 0000000',
			country: 'Pakistan',
			city: 'Lahore',
			headline: 'Calligraphy Creator And Coach',
			about: 'Helping creators build strong visual lettering identities.',
			bio: 'Teaching pen control, script foundations, and layout aesthetics.',
			website: 'https://inkbyhand.app',
			privateAccount: false,
			showEmail: false,
			showPhone: false,
			showOnlineStatus: true,
			searchEngineIndexing: true,
			profileSuggestions: true,
			tagReview: false,
			mentionReview: false,
			currentPassword: '',
			newPassword: '',
			confirmPassword: '',
			recoveryEmail: 'recover@studio.com',
			twoFactor: true,
			loginAlerts: true,
			trustedDevicesOnly: false,
			autoSessionTimeout: true,
			accountType: 'Creator',
			language: 'English',
			timezone: 'Asia/Karachi',
			contentLanguage: 'Both',
			reducedMotion: false,
			highContrast: false,
			largerText: false,
			keyboardNavigation: true,
			notifyLikes: true,
			notifyComments: true,
			notifyMentions: true,
			notifyFollows: true,
			emailDigest: true,
			productUpdates: true,
			marketingEmails: false,
			pushNotifications: true,
			saveWatchHistory: true,
			saveLikeHistory: true,
			personalizedRecommendations: true,
			autoplayVideos: false,
			likedPostsVisible: false,
			watchedPostsVisible: false,
			activityRetention: '90 Days',
			historySync: true,
			dataExportFormat: 'ZIP',
			includeMediaInExport: true,
			backupFrequency: 'Weekly',
			backupEmail: 'backup@studio.com',
			deactivationWindow: '14 Days',
			accountDeletionProtection: true,
			legalConsent: true,
			gdprMode: false
		};

		const CSRF_TOKEN = '{{ csrf_token() }}';
		const PROFILE_KEYS = ['fullName', 'displayName', 'username', 'pronouns', 'email', 'phone', 'country', 'city', 'headline', 'about', 'bio', 'website'];
		const SETTINGS_KEYS = Object.keys(DEFAULT_VALUES).filter((key) => !PROFILE_KEYS.includes(key));
		const state = {
			values: structuredClone(DEFAULT_VALUES),
			original: structuredClone(DEFAULT_VALUES),
			dirty: new Set(),
			activeSection: 'profile',
			filteredSectionIds: null
		};

		const refs = {
			leftNav: document.getElementById('leftNav'),
			sectionHost: document.getElementById('sectionHost'),
			settingsSearch: document.getElementById('settingsSearch'),
			mobileSectionSelect: document.getElementById('mobileSectionSelect'),
			saveAllBtn: document.getElementById('saveAllBtn'),
			discardAllBtn: document.getElementById('discardAllBtn'),
			changeCounter: document.getElementById('changeCounter'),
			syncBadge: document.getElementById('syncBadge'),
			toast: document.getElementById('toast'),
			sectionTemplate: document.getElementById('sectionTemplate'),
			groupTemplate: document.getElementById('groupTemplate')
		};

		function getSectionFieldKeys(sectionId) {
			const section = SETTINGS_SCHEMA.find((s) => s.id === sectionId);
			if (!section) return [];
			return section.groups.flatMap((group) => group.fields.map((field) => field.key));
		}

		function buildPayload() {
			const payload = { settings: {} };

			PROFILE_KEYS.forEach((key) => {
				payload[key] = state.values[key] ?? null;
			});

			SETTINGS_KEYS.forEach((key) => {
				payload.settings[key] = state.values[key];
			});

			return payload;
		}

		async function loadFromApi() {
			const response = await fetch('/api/settings/read', {
				method: 'GET',
				headers: { Accept: 'application/json' }
			});

			if (!response.ok) {
				throw new Error('Unable to read settings from server.');
			}

			const data = await response.json();
			const merged = { ...DEFAULT_VALUES, ...(data.settings || {}) };

			PROFILE_KEYS.forEach((key) => {
				if (data.profile && data.profile[key] !== undefined && data.profile[key] !== null) {
					merged[key] = data.profile[key];
				}
			});

			state.values = merged;
			state.original = structuredClone(merged);
			state.dirty.clear();
		}

		async function saveToApi() {
			const response = await fetch('/api/settings/update', {
				method: 'PUT',
				headers: {
					'Content-Type': 'application/json',
					Accept: 'application/json',
					'X-CSRF-TOKEN': CSRF_TOKEN
				},
				body: JSON.stringify(buildPayload())
			});

			if (!response.ok) {
				throw new Error('Unable to save settings to server.');
			}
		}

		function buildNav() {
			refs.leftNav.innerHTML = '';
			refs.mobileSectionSelect.innerHTML = '';

			const visibleSections = getVisibleSections();
			visibleSections.forEach((section) => {
				const btn = document.createElement('button');
				btn.type = 'button';
				btn.className = 'left-nav-item text-left px-3 py-2.5 border border-slate-200 radius-5 text-sm flex items-center gap-2';
				btn.dataset.section = section.id;
				btn.innerHTML = (SECTION_ICONS[section.id] || '') + '<span>' + section.title + '</span>';
				btn.addEventListener('click', () => activateSection(section.id));
				refs.leftNav.appendChild(btn);

				const option = document.createElement('option');
				option.value = section.id;
				option.textContent = section.title;
				refs.mobileSectionSelect.appendChild(option);
			});
		}

		function getVisibleSections() {
			if (!state.filteredSectionIds) return SETTINGS_SCHEMA;
			return SETTINGS_SCHEMA.filter((s) => state.filteredSectionIds.includes(s.id));
		}

		function buildSections() {
			refs.sectionHost.innerHTML = '';
			const visibleSections = getVisibleSections();

			visibleSections.forEach((section) => {
				const node = refs.sectionTemplate.content.firstElementChild.cloneNode(true);
				node.dataset.section = section.id;
				node.querySelector('.section-title').textContent = section.title;
				node.querySelector('.section-description').textContent = section.description;

				const saveBtn = node.querySelector('.save-section-btn');
				saveBtn.addEventListener('click', () => saveSection(section.id));

				const groupsHost = node.querySelector('.section-groups');
				section.groups.forEach((group) => {
					const groupNode = refs.groupTemplate.content.firstElementChild.cloneNode(true);
					groupNode.querySelector('.group-title').textContent = group.title;
					groupNode.querySelector('.group-description').textContent = group.description;

					const fieldsHost = groupNode.querySelector('.group-fields');
					group.fields.forEach((field) => {
						fieldsHost.appendChild(createFieldNode(field));
					});

					groupsHost.appendChild(groupNode);
				});

				refs.sectionHost.appendChild(node);
			});

			activateSection(visibleSections[0]?.id || 'profile', true);
		}

		function createFieldNode(field) {
			const wrapper = document.createElement('div');
			wrapper.className = 'space-y-1';
			wrapper.dataset.field = field.key;

			if (field.type === 'switch') {
				const row = document.createElement('div');
				row.className = 'flex items-center justify-between p-3 border border-slate-200 bg-white radius-5';

				const labelWrap = document.createElement('div');
				const label = document.createElement('p');
				label.className = 'text-sm font-semibold';
				label.textContent = field.label;
				labelWrap.appendChild(label);

				const input = document.createElement('input');
				input.type = 'checkbox';
				input.className = 'toggle';
				input.checked = Boolean(state.values[field.key]);
				input.dataset.key = field.key;
				input.addEventListener('change', onFieldChange);

				row.appendChild(labelWrap);
				row.appendChild(input);
				wrapper.appendChild(row);
				return wrapper;
			}

			const label = document.createElement('label');
			label.className = 'text-sm font-semibold';
			label.textContent = field.label + (field.required ? ' *' : '');
			wrapper.appendChild(label);

			let input;
			if (field.type === 'select') {
				input = document.createElement('select');
				input.className = 'form-select';
				field.options.forEach((optionValue) => {
					const option = document.createElement('option');
					option.value = optionValue;
					option.textContent = optionValue;
					input.appendChild(option);
				});
			} else if (field.type === 'textarea') {
				input = document.createElement('textarea');
				input.className = 'form-textarea';
				input.rows = field.rows || 3;
			} else {
				input = document.createElement('input');
				input.type = field.type;
				input.className = 'form-input';
			}

			input.dataset.key = field.key;
			input.dataset.type = field.type;
			if (field.required) input.dataset.required = '1';
			input.value = state.values[field.key] ?? '';
			input.addEventListener('input', onFieldChange);

			wrapper.appendChild(input);

			const msg = document.createElement('p');
			msg.className = 'text-xs text-red-500 hidden field-error-msg';
			wrapper.appendChild(msg);

			return wrapper;
		}

		function onFieldChange(event) {
			const el = event.target;
			const key = el.dataset.key;
			if (!key) return;

			const value = el.type === 'checkbox' ? el.checked : el.value;
			state.values[key] = value;

			if (JSON.stringify(state.values[key]) !== JSON.stringify(state.original[key])) {
				state.dirty.add(key);
			} else {
				state.dirty.delete(key);
			}

			validateField(el);
			updateDirtyUI();
		}

		function validateField(el) {
			const key = el.dataset.key;
			const required = el.dataset.required === '1';
			const type = el.dataset.type || el.type;
			const value = (el.type === 'checkbox' ? el.checked : el.value || '').toString().trim();

			const wrapper = el.closest('[data-field]');
			const msg = wrapper ? wrapper.querySelector('.field-error-msg') : null;
			let error = '';

			if (required && !value) error = 'This field is required.';
			if (!error && type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) error = 'Enter a valid email address.';
			if (!error && key === 'phone' && value && !/^\+?[0-9\s\-]{7,20}$/.test(value)) error = 'Enter a valid phone number.';
			if (!error && key === 'confirmPassword' && value !== state.values.newPassword) error = 'Confirm password does not match.';

			if (error) {
				el.classList.add('form-error');
				if (msg) {
					msg.textContent = error;
					msg.classList.remove('hidden');
				}
				return false;
			}

			el.classList.remove('form-error');
			if (msg) {
				msg.textContent = '';
				msg.classList.add('hidden');
			}
			return true;
		}

		function validateSection(sectionId) {
			const sectionEl = document.querySelector(`.section-panel[data-section="${sectionId}"]`);
			if (!sectionEl) return true;

			const fields = sectionEl.querySelectorAll('input:not([type="checkbox"]), select, textarea');
			let allValid = true;
			fields.forEach((field) => {
				if (!validateField(field)) allValid = false;
			});
			return allValid;
		}

		async function saveSection(sectionId) {
			if (!validateSection(sectionId)) {
				showToast('Please fix errors in this section.', 'danger');
				return;
			}

			const section = SETTINGS_SCHEMA.find((s) => s.id === sectionId);
			if (!section) return;

			try {
				await saveToApi();
			} catch (err) {
				showToast('Could not save section to server.', 'danger');
				return;
			}

			getSectionFieldKeys(sectionId).forEach((key) => {
				state.original[key] = structuredClone(state.values[key]);
				state.dirty.delete(key);
			});

			updateDirtyUI();
			showToast(section.title + ' saved.', 'ok');
		}

		async function saveAll() {
			let allValid = true;
			getVisibleSections().forEach((section) => {
				if (!validateSection(section.id)) allValid = false;
			});

			if (!allValid) {
				showToast('Please fix all validation errors first.', 'danger');
				return;
			}

			try {
				await saveToApi();
			} catch (err) {
				showToast('Could not save settings to server.', 'danger');
				return;
			}

			state.original = structuredClone(state.values);
			state.dirty.clear();
			updateDirtyUI();
			showToast('All settings saved successfully.', 'ok');
		}

		function discardAll() {
			state.values = structuredClone(state.original);
			state.dirty.clear();
			buildSections();
			buildNav();
			activateSection(state.activeSection || 'profile', true);
			updateDirtyUI();
			showToast('All unsaved changes discarded.', 'warn');
		}

		function updateDirtyUI() {
			const count = state.dirty.size;
			refs.changeCounter.textContent = count + (count === 1 ? ' unsaved change' : ' unsaved changes');

			if (count > 0) {
				refs.syncBadge.textContent = 'Unsynced';
				refs.syncBadge.className = 'text-[11px] px-2 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold';
			} else {
				refs.syncBadge.textContent = 'Synced';
				refs.syncBadge.className = 'text-[11px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold';
			}
		}

		function activateSection(sectionId, instant = false) {
			const visibleSections = getVisibleSections();
			if (!visibleSections.some((s) => s.id === sectionId)) return;

			state.activeSection = sectionId;

			document.querySelectorAll('.section-panel').forEach((panel) => {
				panel.style.display = panel.dataset.section === sectionId ? 'block' : 'none';
			});

			document.querySelectorAll('.left-nav-item').forEach((item) => {
				item.classList.toggle('active', item.dataset.section === sectionId);
			});

			refs.mobileSectionSelect.value = sectionId;

			const activePanel = document.querySelector(`.section-panel[data-section="${sectionId}"]`);
			if (!activePanel) return;

			if (!instant) {
				gsap.fromTo(activePanel, { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.32, ease: 'power2.out' });
			}
		}

		function applySearch(query) {
			const q = query.trim().toLowerCase();

			if (!q) {
				state.filteredSectionIds = null;
				buildNav();
				buildSections();
				activateSection(state.activeSection || 'profile', true);
				return;
			}

			const matchedIds = SETTINGS_SCHEMA.filter((section) => {
				if (section.title.toLowerCase().includes(q) || section.description.toLowerCase().includes(q)) return true;

				return section.groups.some((group) => {
					if (group.title.toLowerCase().includes(q) || group.description.toLowerCase().includes(q)) return true;
					return group.fields.some((field) => field.label.toLowerCase().includes(q) || field.key.toLowerCase().includes(q));
				});
			}).map((section) => section.id);

			state.filteredSectionIds = matchedIds;
			buildNav();
			buildSections();
			activateSection(matchedIds[0] || 'profile', true);
		}

		function showToast(message, type = 'ok') {
			refs.toast.textContent = message;
			refs.toast.classList.remove('hidden');

			if (type === 'ok') refs.toast.style.background = 'var(--ok)';
			if (type === 'warn') refs.toast.style.background = 'var(--warn)';
			if (type === 'danger') refs.toast.style.background = 'var(--danger)';

			gsap.fromTo(refs.toast, { opacity: 0, y: 14 }, { opacity: 1, y: 0, duration: 0.24, ease: 'power2.out' });

			clearTimeout(showToast._t);
			showToast._t = setTimeout(() => {
				gsap.to(refs.toast, {
					opacity: 0,
					y: 14,
					duration: 0.2,
					onComplete: () => refs.toast.classList.add('hidden')
				});
			}, 1900);
		}

		refs.mobileSectionSelect.addEventListener('change', (e) => activateSection(e.target.value));
		refs.settingsSearch.addEventListener('input', (e) => applySearch(e.target.value));
		refs.saveAllBtn.addEventListener('click', saveAll);
		refs.discardAllBtn.addEventListener('click', discardAll);

		async function initializeSettings() {
			try {
				await loadFromApi();
			} catch (err) {
				showToast('Server settings unavailable. Loaded defaults.', 'warn');
			}

			buildNav();
			buildSections();
			activateSection('profile', true);
			updateDirtyUI();
		}

		initializeSettings();

		gsap.fromTo('header.panel', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.38, ease: 'power2.out' });
		gsap.fromTo('.panel', { opacity: 0, y: 12 }, { opacity: 1, y: 0, duration: 0.3, stagger: 0.05, ease: 'power2.out' });
	</script>
	<script src="{{ asset('app.js') }}"></script>
</body>

</html>
