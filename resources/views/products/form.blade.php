<!DOCTYPE html>
<html>
<head>
    <title>{{ $product ? 'Edit' : 'Add' }} Product</title>
    <script>
        function toggleFields() {
            const type = document.getElementById("type").value;
            document.getElementById("physicalFields").style.display = type === "physical" ? "block" : "none";
            document.getElementById("digitalFields").style.display = type === "digital" ? "block" : "none";
        }

        window.onload = toggleFields;
    </script>
</head>
<body>
    <h2>{{ $product ? 'Edit' : 'Add' }} Product</h2>

    <form method="POST" action="{{ $product ? route('products.update', $product->id) : route('products.store') }}">
        @csrf
        @if($product)
            @method('PUT')
        @endif

        <label>Name: <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required></label><br><br>
        <label>Price: <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required></label><br><br>

        <label>Category:
            <select name="category_id" required>
                <option value="">Select</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label><br><br>

        <label>Type:
            <select name="type" id="type" onchange="toggleFields()" required>
                <option value="physical" {{ old('type', $product->type ?? '') === 'physical' ? 'selected' : '' }}>Physical</option>
                <option value="digital" {{ old('type', $product->type ?? '') === 'digital' ? 'selected' : '' }}>Digital</option>
            </select>
        </label><br><br>

        <div id="physicalFields" style="display:none;">
            <label>Weight (kg): <input type="number" step="0.01" name="weight" value="{{ old('weight', $product->physicalData->weight ?? '') }}"></label><br><br>
        </div>

        <div id="digitalFields" style="display:none;">
            <label>Filesize (MB): <input type="number" step="0.01" name="filesize" value="{{ old('filesize', $product->digitalData->filesize ?? '') }}"></label><br><br>
        </div>

        <button type="submit">{{ $product ? 'Update' : 'Add' }}</button>
        <a href="{{ route('home') }}">Cancel</a>
    </form>
</body>
</html>
