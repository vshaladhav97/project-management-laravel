<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width,
    initial-scale=1.0"> <title>Product Management </title> <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <script
    src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js">
</script> <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-5"> <h1>Product Management</h1> <form id="productForm">
        @csrf
        <div class=" row">
        <div class="col-md-4">
            <input type="text" class="form-control" name="product_name" placeholder="Product Name" required>
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control" name="quantity_in_stock" placeholder="Quantity in Stock" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" class="form-control" name="price_per_item" placeholder="Price per Item"
                required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    </form>

    <div class="mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productTable"></tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                    <td id="totalSum">0</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    </div>

    <script>
        const fetchData = () => {
            $.get("{{ route('products.data') }}", function (data) {
                const products = Object.values(data);

                let tableRows = '';
                let totalSum = 0;

                products.forEach(product => {
                    const totalValue = product.quantity_in_stock * parseFloat(product.price_per_item);
                    totalSum += totalValue;

                    tableRows += `
                <tr id="row-${product.id}">
                    <td class="product-name">${product.product_name}</td>
                    <td class="quantity">${product.quantity_in_stock}</td>
                    <td class="price">${parseFloat(product.price_per_item).toFixed(2)}</td>
                    <td>${new Date(product.created_at).toLocaleString()}</td>
                    <td>${totalValue.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editProduct(${product.id})">Edit</button>
                        <button class="btn btn-success btn-sm d-none" id="save-${product.id}" onclick="saveProduct(${product.id})">Save</button>
                        <button class="btn btn-secondary btn-sm d-none" id="cancel-${product.id}" onclick="cancelEdit(${product.id})">Cancel</button>
                    </td>
                </tr>
                 `;
                });

                $('#productTable').html(tableRows);
                $('#totalSum').text(totalSum.toFixed(2));
            });
        }

        $('#productForm').submit(function (e) {
            e.preventDefault();

            $.post("{{ route('products.store') }}", $(this).serialize(), function (response) {
                if (response.success) {
                    fetchData();
                    $('#productForm')[0].reset();
                }
            });
        });

        const editProduct = (id) => {
            const row = $(`#row-${id}`);
            const productName = row.find('.product-name').text();
            const quantity = row.find('.quantity').text();
            const price = row.find('.price').text();
            row.find('.product-name').html(`<input type="text" class="form-control" id="edit-name-${id}" value="${productName}">`);
            row.find('.quantity').html(`<input type="number" class="form-control" id="edit-quantity-${id}" value="${quantity}">`);
            row.find('.price').html(`<input type="number" step="0.01" class="form-control" id="edit-price-${id}" value="${price}">`);
            row.find('button.btn-warning').addClass('d-none');
            row.find(`#save-${id}, #cancel-${id}`).removeClass('d-none');
        };

        const cancelEdit = (id) => {
            fetchData();
        };

        const saveProduct = (id) => {
            const name = $(`#edit-name-${id}`).val();
            const quantity = $(`#edit-quantity-${id}`).val();
            const price = $(`#edit-price-${id}`).val();

            $.ajax({
                url: `{{ url('/products') }}/${id}`,
                type: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_name: name,
                    quantity_in_stock: quantity,
                    price_per_item: price,
                },
                success: (response) => {
                    if (response.success) {
                        fetchData();
                    }
                },
                error: (xhr) => {
                    console.error('Error updating product:', xhr.responseText);
                },
            });
        };

        $(document).ready(() => fetchData());
    </script>
</body>

</html>