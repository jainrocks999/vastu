<?php

return [
    'name' => 'Products',
    'description' => 'View and update your products settings',
    'create' => 'New product',
    'create_product_type' => [
        'physical' => 'New physical product',
        'digital' => 'New digital product',
    ],
    'edit' => 'Edit product - :name',
    'form' => [
        'name' => 'Name',
        'name_placeholder' => 'Product\'s name (Maximum 120 characters)',
        'home_page_layout'=> 'Show on mobile homepage layout',
        'description' => 'Description',
        'description_placeholder' => 'Short description for product (Maximum 400 characters)',
        'label1' => 'Label1',
        'description1' => 'Product Description1',
        'label2' => 'Label2',
        'description2' => 'Product Description2',
        'label3' => 'Label3',
        'description3' => 'Product Description3',
        'label4' => 'Label4',
        'description4' => 'Product Description4',
        'categories' => 'Categories',
        'content' => 'Content',
        'price' => 'Price',
        'price_helper' => 'Price, exclusive of all Taxes',
        'quantity' => 'Quantity',
        'brand' => 'Brand',
        'width' => 'Width',
        'height' => 'Height',
        'weight' => 'Weight',
        'date' => [
            'start' => 'From date',
            'end' => 'To date',
        ],
        'image' => 'Images',
        'collections' => 'Product collections',
        'labels' => 'Labels',
        'price_sale' => 'Price sale',
        'product_type' => [
            'title' => 'Product type',
        ],
        'product' => 'Product ',
        'total' => 'Total',
        'sub_total' => 'Subtotal',
        'shipping_fee' => 'Shipping fee',
        'discount' => 'Discount',
        'options' => 'Options',
        'shipping' => [
            'height' => 'Height',
            'length' => 'Length',
            'title' => 'Shipping',
            'weight' => 'Weight',
            'wide' => 'Wide',
        ],
        'barcode' => 'Barcode (ISBN, UPC, GTIN, etc.) ',
        'barcode_placeholder' => 'Enter barcode',
        'cost_per_item' => 'Cost per item',
        'cost_per_item_placeholder' => 'Enter cost per item',
        'cost_per_item_helper' => "Customers won't see this price.",
        'stock' => [
            'allow_order_when_out' => 'Allow customer checkout when this product out of stock',
            'in_stock' => 'In stock',
            'out_stock' => 'Out stock',
            'title' => 'Stock status',
        ],
        'storehouse' => [
            'no_storehouse' => 'No storehouse management',
            'storehouse' => 'With storehouse management',
            'title' => 'Storehouse',
            'quantity' => 'Quantity',
        ],
        'tax' => 'Tax',
        'taxes' => 'Taxes',
        'is_default' => 'Is default',
        'action' => 'Action',
        'restock_quantity' => 'Restock quantity',
        'remain' => 'Remain',
        'choose_discount_period' => 'Choose Discount Period',
        'cancel' => 'Cancel',
        'no_results' => 'No results!',
        'value' => 'Value',
        'attribute_name' => 'Attribute name',
        'add_more_attribute' => 'Add more attribute',
        'continue' => 'Continue',
        'add_new_attributes' => 'Add new attributes',
        'add_new_attributes_description' => 'Adding new attributes helps the product to have many options, such as size or color.',
        'create_product_variations' => ':link to create product variations!',
        'tags' => 'Tags',
        'write_some_tags' => 'Write some tags',
        'variation_existed' => 'This variation is existed.',
        'no_attributes_selected' => 'No attributes selected!',
        'added_variation_success' => 'Added variation successfully!',
        'updated_variation_success' => 'Updated variation successfully!',
        'created_all_variation_success' => 'Created all variations successfully!',
        'updated_product_attributes_success' => 'Updated product attributes successfully!',
        'stock_status' => 'Stock status',
        'auto_generate_sku' => 'Auto generate SKU?',
        'featured_image' => 'Featured image',
        'featured_image_helper' => 'Dimension: 800px x 600px',
        'product_id' => 'Product ID',
        'price_sale_percent_helper' => 'Discount :percent from original price.',
        'minimum_order_quantity' => 'Minimum order quantity',
        'minimum_order_quantity_helper' => 'Minimum quantity to place an order, if the value is 0, there is no limit.',
        'maximum_order_quantity' => 'Maximum order quantity',
        'maximum_order_quantity_helper' => 'Maximum quantity to place an order, if the value is 0, there is no limit.',
        'video' => 'Video',
        'video_file' => 'File',
        'video_url' => 'Or External Video URL',
        'video_url_help' => 'Enter YouTube or Vimeo video URL',
        'enter_video_url' => 'Enter video URL',
        'video_thumbnail' => 'Video thumbnail',
        'video_thumbnail_help' => 'Choose a thumbnail for the video. If not selected, it will be automatically get from the video URL if available.',
    ],
    'price' => 'Price',
    'quantity' => 'Quantity',
    'type' => 'Type',
    'image' => 'Thumbnail',
    'sku' => 'SKU',
    'sku_line' => 'SKU: :sku',
    'variation_sku' => 'Variation SKU',
    'brand' => 'Brand',
    'cannot_delete' => 'Product could not be deleted',
    'product_deleted' => 'Product deleted',
    'product_collections' => 'Product collections',
    'products' => 'Products',
    'menu' => 'Products',
    'control' => [
        'button_add_image' => 'Add image',
    ],
    'price_sale' => 'Sale price',
    'price_group_title' => 'Manager product price',
    'store_house_group_title' => 'Manager store house',
    'shipping_group_title' => 'Manager shipping',
    'overview' => 'Overview',
    'attributes' => 'Attributes',
    'product_has_variations' => 'Product has variations',
    'manage_products' => 'Manage products',
    'add_new_product' => 'Add a new product',
    'start_by_adding_new_product' => 'Start by adding new products.',
    'edit_this_product' => 'Edit this product',
    'delete' => 'Delete',
    'related_products' => 'Related products',
    'cross_selling_products' => 'Cross-selling products',
    'up_selling_products' => 'Up-selling products',
    'grouped_products' => 'Grouped products',
    'search_products' => 'Search products',
    'selected_products' => 'Selected products',
    'edit_variation_item' => 'Edit',
    'variations_box_description' => 'Click on "Edit attribute" to add/remove attributes of variation or click on "Add new variation" to add variation.',
    'save_changes' => 'Save changes',
    'continue' => 'Continue',
    'edit_attribute' => 'Edit attribute',
    'select_attribute' => 'Select attribute',
    'add_new_variation' => 'Add new variation',
    'edit_variation' => 'Edit variation',
    'generate_variations' => 'Generate variations',
    'generate_variations_confirmation' => 'Are you sure you want to generate variations for this product?',
    'delete_variation' => 'Delete variation?',
    'delete_variation_confirmation' => 'Are you sure you want to delete this variation? This action cannot be undo.',
    'delete_variations_confirmation' => 'Are you sure you want to delete those variations? This action cannot be undo.',
    'product_create_validate_name_required' => 'Please enter product\'s name',
    'product_create_validate_sale_price_max' => 'The discount must be less than the original price',
    'product_create_validate_cost_per_item_max' => 'The cost per item must be less than the original price',
    'product_create_validate_sale_price_required_if' => 'Must enter a discount when you want to schedule a promotion',
    'product_create_validate_end_date_after' => 'End date must be after start date',
    'product_create_validate_start_date_required_if' => 'Discount start date cannot be left blank when scheduling is selected',
    'product_create_validate_sale_price' => 'Discounts cannot be left blank when scheduling is selected',
    'stock_statuses' => [
        'in_stock' => 'In stock',
        'out_of_stock' => 'Out of stock',
        'on_backorder' => 'On backorder',
    ],
    'stock_status' => 'Stock status',
    'processing' => 'Processing...',
    'delete_selected_variations' => 'Delete selected variations',
    'delete_variations' => 'Delete variations',
    'category' => 'Category',
    'product_price_flash_sale_warning' => 'This product is in flash sale <strong>:name</strong> so its price is <strong>:price</strong>.',
    'product_price_discount_warning' => 'This product is in discount <strong>:name</strong> so its price is <strong>:price</strong>.',
    'product_image' => 'Product image',
    'product_name' => 'Product name',
    'product_link' => 'Product link',
    'types' => [
        'physical' => 'Physical',
        'digital' => 'Digital',
    ],
    'digital_attachments' => [
        'title' => 'Digital attachments',
        'add' => 'Add Attachment',
        'file_name' => 'File name',
        'file_size' => 'File size',
        'unsaved' => 'Unsaved',
        'add_external_link' => 'Add external link',
        'enter_file_name' => 'Enter file name',
        'enter_external_link_download' => 'Enter external link download',
        'enter_file_size' => 'Enter file size',
        'external_link_download' => 'External link download',
        'generate_license_code_after_purchasing_product' => 'Generate license code after purchasing this product?',
        'notify_attachment_updated' => 'Send an email to customer when attachment is updated',
    ],
    'this_action_will_reload_page' => 'This action will reload the page to update the data!',
    'select' => 'Select',
    'set_this_variant_as_default' => 'Set this variant as default',
    'download' => 'Download',
    'cross_sell_price_type' => [
        'title' => 'Price type',
        'fixed' => 'Fixed',
        'percent' => 'Percent',
    ],
    'cross_sell_help' => [
        'price' => '* Price field',
        'price_description' => 'Enter the amount you want to reduce from the original price. Example: If the original price is $100, enter 20 to reduce the price to $80.',
        'type' => '* Type field',
        'type_description' => 'Choose the discount type: Fixed (reduce a specific amount) or Percent (reduce by a percentage).',
    ],
    'export' => [
        'description' => 'Export your product data to CSV or Excel files.',
        'total_items' => 'Total Items',
        'total_products' => 'Total Products',
        'total_variations' => 'Total Variations',
    ],
    'import' => [
        'description' => 'Import your product data from CSV or Excel files.',
        'rules' => [
            'in' => ':attribute must be one of defined values: :values.',
            'required_string_max' => ':attribute is required and must be a string with a maximum length of :max characters.',
            'nullable_string' => ':attribute can be left empty or must be a string if provided.',
            'nullable_string_max' => ':attribute can be left empty or must be a string with a maximum length of :max characters if provided',
            'nullable_bool' => 'Whether :attribute can be left empty or must be a boolean value if provided.',
            'nullable_numeric_min' => ':attribute can be left empty or must be a non-negative number if provided.',
            'nullable_numeric_min_max' => ':attribute can be leave blank or must be a number between :min and :max if provided.',
            'nullable_array' => ':attribute can be left empty or must be provided as an array if specified.',
            'nullable_date_required_if' => ':attribute can be left empty or must be a valid date if provided, required if :required is specified.',
            'nullable_date_after' => ':attribute can be left empty or must be a valid date after the :after if provided.',
        ],
        'updated_message' => 'Updated :count products successfully!',
    ],
    'import_products_intro' => 'Or import products from CSV/Excel',
    'select_attributes' => 'Select attributes to create variations:',
    'all' => 'All',
];
