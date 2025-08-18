<x-admin.layout title="Manage Blog Posts">
    <div class="content-card card">
        <div style="display:flex; justify-content: space-between; align-items: flex-start; margin-bottom:2rem;">
            <div class="heading">
                <h2 style="margin:0 0 0.25rem 0;">Manage Blog Posts</h2>
                <p style="margin:0; color:#6b7280;">View, edit, and delete your blog posts.</p>
            </div>

            <a href="{{ route('admin.blogs.create') }}" class="button is-primary" style="display:flex; align-items:center; gap:0.5rem;">
                <span style="font-size:1.25rem; line-height:1;">+</span> Add New Post
            </a>
        </div>

        @if(session('success'))
            <div class="alert success" style="margin-bottom:1.5rem;">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($blogs->count() > 0)
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $blog)
                            <tr data-status="{{ $blog->is_published ? 'published' : 'draft' }}" 
                                data-type="{{ $blog->is_ai_generated ? 'ai-generated' : 'manual' }}">
                                <td>
                                    <div>
                                        <strong>{{ $blog->title }}</strong>
                                        @if($blog->description)
                                            <br>
                                            <small style="color: #6b7280;">{{ Str::limit($blog->description, 60) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $blog->author->name ?? 'Unknown' }}</td>
                                <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($blog->is_published)
                                        <span class="badge success">Published</span>
                                    @else
                                        <span class="badge draft">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.5rem; justify-content:center;">
                                                                                 @if($blog->is_published && $blog->slug)
                                             <a href="/blog/{{ $blog->slug }}" target="_blank" class="icon-button" title="Visit on Website" style="color:#10b981;">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
                                                    <path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="icon-button" title="Edit">
                                            <x-admin.icon.mini.edit />
                                        </a>
                                        <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="icon-button" title="Delete" style="color:#dc2626;">
                                                <x-admin.icon.mini.trash />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $blogs->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;">
                <h3 style="color: #6b7280; margin-bottom: 1rem;">No blog posts yet</h3>
                <p style="color: #9ca3af; margin-bottom: 2rem;">Start creating engaging content for your audience</p>
                <a href="{{ route('admin.blogs.create') }}" class="button is-primary">
                    Create Your First Blog Post
                </a>
            </div>
        @endif
    </div>

    <script>
        function filterPosts(filter) {
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const status = row.dataset.status;
                const type = row.dataset.type;
                
                let show = false;
                
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'published':
                        show = status === 'published';
                        break;
                    case 'draft':
                        show = status === 'draft';
                        break;
                    case 'ai-generated':
                        show = type === 'ai-generated';
                        break;
                }
                
                row.style.display = show ? '' : 'none';
            });
        }
    </script>

    <style>
        /* Table aesthetics */
        .table-wrapper {
            overflow-x:auto;
        }
        .table thead th {
            text-align:left;
            font-size:0.75rem;
            font-weight:700;
            color:#6b7280;
            text-transform:uppercase;
            padding:0.75rem 1rem;
            white-space:nowrap;
        }
        .table tbody td {
            padding:1rem;
            border-top:1px solid #e5e7eb;
            vertical-align:middle;
        }

        .icon-button {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:32px;
            height:32px;
            border-radius:6px;
            background:transparent;
            color:#374151;
            transition:background-color 150ms ease-in-out;
            border:none;
        }
        .icon-button:hover {
            background-color:#f3f4f6;
        }
        .icon-button svg {
            width:1rem;
            height:1rem;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        
        .badge.success {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge.draft {
            background-color: #fef9c3;
            color: #92400e;
        }
    </style>
</x-admin.layout>
