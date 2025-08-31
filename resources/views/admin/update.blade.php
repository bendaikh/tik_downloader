<x-admin.layout title="System Updates">

    <div class="content-card card">
        <div class="heading">
            <h2>System Updates</h2>
            <p>Upload and manage application updates</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="update-info">
            <div class="current-version">
                <h3>Current Version</h3>
                <div class="version-badge">{{ $currentVersion }}</div>
            </div>
        </div>

        <div class="update-sections">
            <!-- Upload Update Section -->
            <div class="update-section">
                <h3>Upload Update</h3>
                <div class="upload-area">
                    <form action="{{ route('admin.update.upload') }}" method="POST" enctype="multipart/form-data" id="updateForm">
                        @csrf
                        <div class="file-upload-container">
                            <div class="file-upload-area" id="uploadArea">
                                <div class="upload-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7,10 12,15 17,10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <h4>Drop your update file here</h4>
                                    <p>or click to browse</p>
                                    <small>Supported format: ZIP file (max 100MB)</small>
                                </div>
                                <input type="file" name="update_file" id="updateFile" accept=".zip" style="display: none;">
                            </div>
                            <div class="file-info" id="fileInfo" style="display: none;">
                                <div class="file-details">
                                    <span class="file-name" id="fileName"></span>
                                    <span class="file-size" id="fileSize"></span>
                                </div>
                                <button type="button" class="remove-file" id="removeFile">Remove</button>
                            </div>
                        </div>
                        
                        <div class="upload-requirements">
                            <h4>Update Package Requirements:</h4>
                            <ul>
                                <li>Must be a valid ZIP file</li>
                                <li>Must contain <code>update.json</code> file with version information</li>
                                <li>Must contain <code>files/</code> directory with updated files</li>
                                <li>Version must be higher than current version ({{ $currentVersion }})</li>
                            </ul>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="button is-primary" id="uploadBtn" disabled>
                                <span class="button-text">Upload & Apply Update</span>
                                <span class="button-loading" style="display: none;">
                                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" stroke-dasharray="31.416" stroke-dashoffset="31.416">
                                            <animate attributeName="stroke-dasharray" dur="2s" values="0 31.416;15.708 15.708;0 31.416" repeatCount="indefinite"/>
                                            <animate attributeName="stroke-dashoffset" dur="2s" values="0;-15.708;-31.416" repeatCount="indefinite"/>
                                        </circle>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update History Section -->
            <div class="update-section">
                <h3>Update History</h3>
                @if(count($updateHistory) > 0)
                    <div class="update-history">
                        @foreach($updateHistory as $update)
                            <div class="update-item">
                                <div class="update-header">
                                    <div class="update-version">
                                        <span class="version-badge">{{ $update['version'] }}</span>
                                        <span class="update-date">{{ \Carbon\Carbon::parse($update['date'])->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                                @if(!empty($update['description']))
                                    <div class="update-description">
                                        {{ $update['description'] }}
                                    </div>
                                @endif
                                @if(!empty($update['changes']))
                                    <div class="update-changes">
                                        <h5>Changes:</h5>
                                        <ul>
                                            @foreach($update['changes'] as $change)
                                                <li>{{ $change }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-updates">
                        <p>No updates have been applied yet.</p>
                    </div>
                @endif
            </div>

            <!-- Backup Section -->
            <div class="update-section">
                <h3>Backup Management</h3>
                <div class="backup-info">
                    <p>Automatic backups are created before each update. You can download previous backups if needed.</p>
                    <button type="button" class="button is-secondary" id="refreshBackups">
                        Refresh Backup List
                    </button>
                </div>
                <div class="backup-list" id="backupList">
                    <div class="loading">Loading backups...</div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .update-info {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1rem;
            color: white;
        }

        .current-version h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .version-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .update-sections {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .update-section {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .update-section h3 {
            margin: 0 0 1rem 0;
            color: #1f2937;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .file-upload-container {
            margin-bottom: 1.5rem;
        }

        .file-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .file-upload-area:hover {
            border-color: #6366f1;
            background: #f0f9ff;
        }

        .file-upload-area.dragover {
            border-color: #6366f1;
            background: #eff6ff;
            transform: scale(1.02);
        }

        .upload-icon {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .upload-text h4 {
            margin: 0 0 0.5rem 0;
            color: #374151;
            font-size: 1.1rem;
        }

        .upload-text p {
            margin: 0 0 0.5rem 0;
            color: #6b7280;
        }

        .upload-text small {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .file-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 0.75rem;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .file-name {
            font-weight: 600;
            color: #0c4a6e;
        }

        .file-size {
            font-size: 0.875rem;
            color: #0369a1;
        }

        .remove-file {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s ease;
        }

        .remove-file:hover {
            background: #dc2626;
        }

        .upload-requirements {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .upload-requirements h4 {
            margin: 0 0 0.75rem 0;
            color: #92400e;
            font-size: 1rem;
        }

        .upload-requirements ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #92400e;
        }

        .upload-requirements li {
            margin-bottom: 0.25rem;
        }

        .upload-requirements code {
            background: #fbbf24;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        .form-actions {
            text-align: center;
        }

        .button {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .button.is-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .button.is-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
        }

        .button.is-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .button.is-secondary {
            background: #6b7280;
            color: white;
        }

        .button.is-secondary:hover {
            background: #4b5563;
        }

        .spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .update-history {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .update-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .update-header {
            margin-bottom: 0.75rem;
        }

        .update-version {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .update-date {
            color: #64748b;
            font-size: 0.875rem;
        }

        .update-description {
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .update-changes h5 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .update-changes ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #4b5563;
        }

        .update-changes li {
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .no-updates {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .backup-info {
            margin-bottom: 1rem;
        }

        .backup-info p {
            margin: 0 0 1rem 0;
            color: #6b7280;
        }

        .backup-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .backup-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .backup-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .backup-name {
            font-weight: 600;
            color: #1f2937;
        }

        .backup-meta {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .backup-download {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .backup-download:hover {
            background: #059669;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const updateFile = document.getElementById('updateFile');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFile = document.getElementById('removeFile');
            const uploadBtn = document.getElementById('uploadBtn');
            const updateForm = document.getElementById('updateForm');
            const refreshBackups = document.getElementById('refreshBackups');
            const backupList = document.getElementById('backupList');

            // File upload handling
            uploadArea.addEventListener('click', () => updateFile.click());
            
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            updateFile.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });

            removeFile.addEventListener('click', () => {
                updateFile.value = '';
                fileInfo.style.display = 'none';
                uploadArea.style.display = 'block';
                uploadBtn.disabled = true;
            });

            function handleFileSelect(file) {
                if (file.type !== 'application/zip' && !file.name.endsWith('.zip')) {
                    alert('Please select a valid ZIP file.');
                    return;
                }

                if (file.size > 100 * 1024 * 1024) { // 100MB
                    alert('File size must be less than 100MB.');
                    return;
                }

                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.style.display = 'flex';
                uploadArea.style.display = 'none';
                uploadBtn.disabled = false;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Form submission
            updateForm.addEventListener('submit', function(e) {
                const buttonText = uploadBtn.querySelector('.button-text');
                const buttonLoading = uploadBtn.querySelector('.button-loading');
                
                buttonText.style.display = 'none';
                buttonLoading.style.display = 'flex';
                uploadBtn.disabled = true;
            });

            // Backup management
            refreshBackups.addEventListener('click', loadBackups);
            
            function loadBackups() {
                backupList.innerHTML = '<div class="loading">Loading backups...</div>';
                
                fetch('/admin/update/backups')
                    .then(response => response.json())
                    .then(backups => {
                        if (backups.length === 0) {
                            backupList.innerHTML = '<div class="no-updates">No backups found.</div>';
                            return;
                        }

                        backupList.innerHTML = backups.map(backup => `
                            <div class="backup-item">
                                <div class="backup-details">
                                    <div class="backup-name">${backup.name}</div>
                                    <div class="backup-meta">${backup.date} • ${formatFileSize(backup.size)}</div>
                                </div>
                                <a href="/admin/update/backup/${backup.name}" class="backup-download">Download</a>
                            </div>
                        `).join('');
                    })
                    .catch(error => {
                        backupList.innerHTML = '<div class="loading">Error loading backups.</div>';
                        console.error('Error:', error);
                    });
            }

            // Load backups on page load
            loadBackups();
        });
    </script>
    @endpush
</x-admin.layout>
