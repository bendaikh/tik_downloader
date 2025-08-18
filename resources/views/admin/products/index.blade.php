<x-admin.layout title="Products">
    <div class="content-card card">
        <div class="heading">
            <h2>Products</h2>
            <p>Manage your affiliate products.</p>
        </div>

        @push('header')
            <button onclick="openCreateModal()" class="button is-primary">+ Create Product</button>
        @endpush

        @if(session('success'))
            <div class="alert success">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($products->count() > 0)
            <div class="products-table-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>PRODUCT</th>
                            <th>DESCRIPTION</th>
                            <th>AFFILIATE LINK</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($products as $product)
                            <tr>
                                <td class="product-cell">
                                    <div class="product-info">
                                        <div class="product-image">
                            @if($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                            @else
                                                <div class="product-placeholder">📦</div>
                                            @endif
                                        </div>
                                        <span class="product-name">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="description-cell">
                                    <span class="description-text">{{ Str::limit($product->description, 80) }}</span>
                                </td>
                                <td class="link-cell">
                                    <a href="{{ $product->affiliate_url }}" target="_blank" class="affiliate-link">
                                        {{ Str::limit($product->affiliate_url, 40) }}
                                    </a>
                                </td>
                                <td class="actions-cell">
                                    <div class="actions-container">
                                        <button onclick="openEditModal({{ $product->id }}, {{ json_encode($product) }})" class="action-button edit-button" title="Modify">
                                            ✏️
                                        </button>
                                        <button onclick="openDeleteModal({{ $product->id }}, '{{ $product->name }}')" class="action-button delete-button" title="Delete">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-container">
                {{ $products->links() }}
            </div>
                            @else
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h3>No products yet</h3>
                <p>Create your first affiliate product to get started.</p>
                <button onclick="openCreateModal()" class="button is-primary">
                    + Create Product
                </button>
            </div>
                            @endif
                        </div>
                        
    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Product</h3>
                <button onclick="closeEditModal()" class="modal-close">&times;</button>
            </div>
            
            <form id="editProductForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editProductId" name="product_id">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="edit_name" class="form-input" required>
                        <span class="form-error" id="edit_name-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-input" rows="4" required></textarea>
                        <span class="form-error" id="edit_description-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="edit_affiliate_url" class="form-label">Affiliate URL</label>
                        <input type="url" name="affiliate_url" id="edit_affiliate_url" class="form-input" placeholder="https://example.com/product" required>
                        <span class="form-error" id="edit_affiliate_url-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_price" class="form-label">Price (Optional)</label>
                            <input type="number" name="price" id="edit_price" class="form-input" step="0.01" min="0">
                            <span class="form-error" id="edit_price-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="edit_currency" class="form-label">Currency</label>
                            <select name="currency" id="edit_currency" class="form-input">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="CAD">CAD</option>
                                <option value="AUD">AUD</option>
                            </select>
                            <span class="form-error" id="edit_currency-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_image" class="form-label">Product Image</label>
                        <input type="file" name="image" id="edit_image" class="form-input" accept="image/*">
                        <small>Max size: 2MB. Formats: JPEG, PNG, JPG, GIF, WebP</small>
                        <span class="form-error" id="edit_image-error"></span>
                        <div id="current-image" style="margin-top: 10px; display: none;">
                            <small>Current image:</small><br>
                            <img id="current-image-preview" src="" style="max-width: 100px; max-height: 100px; border-radius: 4px;">
                            </div>
                        </div>
                        
                    <div class="form-group">
                        <label for="edit_sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="form-input" value="0" min="0">
                        <small>Lower numbers appear first</small>
                        <span class="form-error" id="edit_sort_order-error"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <span class="form-checkbox-label">Active</span>
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeEditModal()" class="button">Cancel</button>
                    <button type="submit" class="button is-primary">Update Product</button>
                </div>
                            </form>
                        </div>
                    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteProductModal" class="modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Delete Product</h3>
                <button onclick="closeDeleteModal()" class="modal-close">&times;</button>
            </div>
            
            <div class="modal-body">
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;">⚠️</div>
                    <h4 style="margin-bottom: 0.5rem; color: #1f2937;">Are you sure?</h4>
                    <p style="color: #6b7280; margin-bottom: 0;">Do you really want to delete "<span id="delete-product-name" style="font-weight: 600;"></span>"? This action cannot be undone.</p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeDeleteModal()" class="button">Cancel</button>
                <button type="button" onclick="confirmDelete()" class="button" style="background: #ef4444; color: white; border-color: #ef4444;">Delete Product</button>
            </div>
        </div>
    </div>

    <!-- Create Product Modal -->
    <div id="createProductModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Product</h3>
                <button onclick="closeCreateModal()" class="modal-close">&times;</button>
            </div>
            
            <form id="createProductForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-input" required>
                        <span class="form-error" id="name-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-input" rows="4" required></textarea>
                        <span class="form-error" id="description-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="affiliate_url" class="form-label">Affiliate URL</label>
                        <input type="url" name="affiliate_url" id="affiliate_url" class="form-input" placeholder="https://example.com/product" required>
                        <span class="form-error" id="affiliate_url-error"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="form-label">Price (Optional)</label>
                            <input type="number" name="price" id="price" class="form-input" step="0.01" min="0">
                            <span class="form-error" id="price-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="currency" class="form-label">Currency</label>
                            <select name="currency" id="currency" class="form-input">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="GBP">GBP</option>
                                <option value="CAD">CAD</option>
                                <option value="AUD">AUD</option>
                            </select>
                            <span class="form-error" id="currency-error"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" name="image" id="image" class="form-input" accept="image/*">
                        <small>Max size: 2MB. Formats: JPEG, PNG, JPG, GIF, WebP</small>
                        <span class="form-error" id="image-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" class="form-input" value="0" min="0">
                        <small>Lower numbers appear first</small>
                        <span class="form-error" id="sort_order-error"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-checkbox">
                            <input type="checkbox" name="is_active" value="1" checked>
                            <span class="form-checkbox-label">Active</span>
                        </label>
                    </div>
            </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeCreateModal()" class="button">Cancel</button>
                    <button type="submit" class="button is-primary">Create Product</button>
                </div>
            </form>
            </div>
    </div>

    @push('styles')
    <style>
        .products-table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e5e9;
        }

        .products-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .products-table thead {
            background: #f8f9fa;
        }

        .products-table th {
            padding: 18px 24px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-right: 1px solid #e1e5e9;
            border-bottom: 1px solid #e1e5e9;
        }

        .products-table th:last-child {
            border-right: none;
        }

        .products-table td {
            padding: 20px 24px;
            border-right: 1px solid #e1e5e9;
            border-bottom: 1px solid #e1e5e9;
            vertical-align: middle;
        }

        .products-table td:last-child {
            border-right: none;
        }

        .products-table tbody tr:last-child td {
            border-bottom: none;
        }

        .products-table tbody tr:hover {
            background: #f8f9fa;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            overflow: hidden;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-placeholder {
            font-size: 1.5rem;
            color: #9ca3af;
        }

        .product-name {
            font-weight: 500;
            color: #1f2937;
            font-size: 15px;
            line-height: 1.4;
        }

        .description-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.5;
        }

        .affiliate-link {
            color: #4f46e5;
            text-decoration: none;
            font-size: 14px;
        }

        .affiliate-link:hover {
            text-decoration: underline;
        }

        .actions-cell {
            text-align: center;
            width: 120px;
            padding: 12px !important;
        }

        .actions-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 2px solid;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .edit-button {
            color: #3b82f6 !important;
            background: #ffffff !important;
            border-color: #3b82f6 !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .edit-button:hover {
            color: #ffffff !important;
            background: #3b82f6 !important;
            border-color: #2563eb !important;
        }

        .delete-button {
            color: #ef4444 !important;
            border-color: #ef4444 !important;
            background: #ffffff !important;
        }

        .delete-button:hover {
            color: #ffffff !important;
            background: #ef4444 !important;
            border-color: #dc2626 !important;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .empty-state p {
            margin-bottom: 1.5rem;
        }

        /* Pagination */
        .pagination-container nav {
            display: flex;
            justify-content: center;
            margin: 1.5rem 0;
        }
        .pagination-container nav ul {
            display: flex;
            gap: 0.25rem;
            list-style: none;
        }
        .pagination-container nav li a,
        .pagination-container nav span {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            color: #374151;
            text-decoration: none;
        }
        .pagination-container nav li a:hover {
            background: #f9fafb;
        }
        .pagination-container nav li span[aria-current="page"] {
            background: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: #374151;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-error {
            display: block;
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-checkbox input[type="checkbox"] {
            width: auto;
        }

        .form-checkbox-label {
            font-size: 0.875rem;
            color: #374151;
        }

        small {
            display: block;
            color: #6b7280;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('createProductModal').style.display = 'flex';
        }

        function closeCreateModal() {
            document.getElementById('createProductModal').style.display = 'none';
            document.getElementById('createProductForm').reset();
            clearErrors();
        }

        function clearErrors() {
            document.querySelectorAll('.form-error').forEach(error => {
                error.textContent = '';
            });
        }

        let currentEditProductId = null;
        let currentDeleteProductId = null;

        function openEditModal(productId, productData) {
            currentEditProductId = productId;
            
            // Fill form with product data
            document.getElementById('editProductId').value = productId;
            document.getElementById('edit_name').value = productData.name;
            document.getElementById('edit_description').value = productData.description;
            document.getElementById('edit_affiliate_url').value = productData.affiliate_url;
            document.getElementById('edit_price').value = productData.price || '';
            document.getElementById('edit_currency').value = productData.currency || 'USD';
            document.getElementById('edit_sort_order').value = productData.sort_order || 0;
            document.getElementById('edit_is_active').checked = productData.is_active;
            
            // Show current image if exists
            if (productData.image_url) {
                document.getElementById('current-image').style.display = 'block';
                document.getElementById('current-image-preview').src = productData.image_url;
            } else {
                document.getElementById('current-image').style.display = 'none';
            }
            
            clearEditErrors();
            document.getElementById('editProductModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editProductModal').style.display = 'none';
            document.getElementById('editProductForm').reset();
            document.getElementById('current-image').style.display = 'none';
            clearEditErrors();
            currentEditProductId = null;
        }

        function clearEditErrors() {
            document.querySelectorAll('[id^="edit_"][id$="-error"]').forEach(error => {
                error.textContent = '';
            });
        }

        function openDeleteModal(productId, productName) {
            currentDeleteProductId = productId;
            document.getElementById('delete-product-name').textContent = productName;
            document.getElementById('deleteProductModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteProductModal').style.display = 'none';
            currentDeleteProductId = null;
        }

        function confirmDelete() {
            if (!currentDeleteProductId) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${currentDeleteProductId}`;
            
            // Get CSRF token safely
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfValue = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = csrfValue;
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }

        // Handle edit form submission
        document.getElementById('editProductForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!currentEditProductId) return;
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Updating...';
            submitButton.disabled = true;
            
            clearEditErrors();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch(`/admin/products/${currentEditProductId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    location.reload();
                } else if (response.status === 422) {
                    // Validation errors
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById('edit_' + field + '-error');
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }
        });

        // Handle create form submission
        document.getElementById('createProductForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Creating...';
            submitButton.disabled = true;
            
            clearErrors();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("admin.products.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    location.reload();
                } else if (response.status === 422) {
                    // Validation errors
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + '-error');
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                } else {
                    alert('An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            } finally {
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }
        });

        // Close modals when clicking outside
        document.getElementById('createProductModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateModal();
            }
        });

        document.getElementById('editProductModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        document.getElementById('deleteProductModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const createModal = document.getElementById('createProductModal');
                const editModal = document.getElementById('editProductModal');
                const deleteModal = document.getElementById('deleteProductModal');
                
                if (createModal.style.display === 'flex') {
                    closeCreateModal();
                } else if (editModal.style.display === 'flex') {
                    closeEditModal();
                } else if (deleteModal.style.display === 'flex') {
                    closeDeleteModal();
                }
            }
        });
    </script>
    @endpush
</x-admin.layout>
