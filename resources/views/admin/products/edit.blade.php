<x-admin.layout title="Edit Product">
    <div class="content-card card">
        <div class="heading">
            <h2>Edit Product</h2>
            <p>Update product details.</p>
        </div>

        <form method="POST" action="{{route('admin.products.update', $product)}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="affiliate_url" class="form-label">Affiliate URL</label>
                <input type="url" name="affiliate_url" id="affiliate_url" class="form-input" value="{{ old('affiliate_url', $product->affiliate_url) }}" placeholder="https://example.com/product" required>
                @error('affiliate_url')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="price" class="form-label">Price (Optional)</label>
                    <input type="number" name="price" id="price" class="form-input" value="{{ old('price', $product->price) }}" step="0.01" min="0">
                    @error('price')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="currency" class="form-label">Currency</label>
                    <select name="currency" id="currency" class="form-input">
                        <option value="USD" {{ old('currency', $product->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency', $product->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency', $product->currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                        <option value="CAD" {{ old('currency', $product->currency) == 'CAD' ? 'selected' : '' }}>CAD</option>
                        <option value="AUD" {{ old('currency', $product->currency) == 'AUD' ? 'selected' : '' }}>AUD</option>
                    </select>
                    @error('currency')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="image" class="form-label">Product Image</label>
                @if($product->image_url)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #e1e5e9;">
                        <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">Current image</p>
                    </div>
                @endif
                <input type="file" name="image" id="image" class="form-input" accept="image/*">
                <small style="color: #6b7280; font-size: 0.875rem;">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF, WebP. Leave empty to keep current image.</small>
                @error('image')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="sort_order" class="form-label">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', $product->sort_order) }}" min="0">
                <small style="color: #6b7280; font-size: 0.875rem;">Lower numbers appear first</small>
                @error('sort_order')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-checkbox">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <span class="form-checkbox-label">Active</span>
                </label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="button is-primary">Update Product</button>
                <a href="{{route('admin.products.index')}}" class="button">Cancel</a>
            </div>
        </form>
    </div>
</x-admin.layout>
