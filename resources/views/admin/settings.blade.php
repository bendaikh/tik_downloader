<x-admin.layout title="Settings">

    <div class="content-card card">
        <div class="heading">
            <h2>Settings</h2>
            <p>Modify the sites Settings</p>
        </div>

        <form
            action="{{route('admin.settings.update')}}"
            method="POST"
            id="settingsForm"
        >
            @csrf
            <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('name')])>
                <label for="name">App Name</label>
                <input type="text" id="name" name="name" value="{{config('app.name')}}" required>
                <small style="color: #64748b; margin-top: 0.5rem; display: block;">This name will be displayed as the logo on your landing page</small>
                @error('name')
                <div class="error">{{$message}}</div>
                @enderror
            </div>
            <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('description')])>
                <label for="description">Site Description</label>
                <textarea id="description" name="description" required rows="5">{{config('app.description')}}</textarea>
                @error('description')
                <div class="error">{{$message}}</div>
                @enderror
            </div>
            <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('keywords')])>
                <label for="keywords">Site Keywords</label>
                <input type="text" id="keywords" name="keywords" value="{{config('app.keywords')}}" required>
                @error('keywords')
                <div class="error">{{$message}}</div>
                @enderror
            </div>
            <div @class(['form-element', 'is-required', 'is-error'=> $errors->has('link_per_bitrate')])>
                <label for="link_per_bitrate">Download Links per each Bitrate</label>

                <select id="link_per_bitrate" name="link_per_bitrate" required>
                    @foreach([1,2,3] as $perBitrate)
                        <option
                            value="{{$perBitrate}}" {{old('protocol', config('app.link_per_bitrate')) == $perBitrate ? 'selected' : ''}}>
                            {{$perBitrate . ' '. str('link')->pluralStudly($perBitrate) . ' per each bitrate'}}
                        </option>
                    @endforeach
                </select>

                @error('link_per_bitrate')
                <div class="error">{{$message}}</div>
                @enderror
            </div>
            @push('header')
                <button class="button is-primary" type="submit" form="settingsForm">Save</button>
            @endpush
        </form>
    </div>
</x-admin.layout>



