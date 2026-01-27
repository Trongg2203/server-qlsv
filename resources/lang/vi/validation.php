<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted" => ":attribute phải được chấp nhận.",
    "accepted_if" => ":attribute phải được chấp nhận khi :other là :value.",
    "active_url" => ":attribute không phải là một URL hợp lệ.",
    "after" => ":attribute phải là một ngày sau :date.",
    "after_or_equal" => ":attribute phải là ngày sau hoặc bằng :date.",
    "alpha" => ":attribute chỉ được chứa các chữ cái.",
    "alpha_dash" => ":attribute chỉ được chứa các chữ cái, số, dấu gạch ngang và dấu gạch dưới.",
    "alpha_num" => ":attribute chỉ được chứa các chữ cái và số .",
    "array" => ":attribute phải là một mảng.",
    "before" => ":attribute phải là một ngày trước :date.",
    "before_or_equal" => ":attribute phải là một ngày trước hoặc bằng :date",
    "between" => [
        "numeric" => ":attribute phải ở giữa :min và :max",
        "file" => ":attribute phải ở giữa :min và :max kilobytes",
        "string" => ":attribute phải ở giữa :min và :max ký tự",
        "array" => ":attribute phải ở giữa :min và :max giá trị"
    ],
    "boolean" => ":attribute phải là true hoặc false",
    "confirmed" => ":attribute nhận định không phù hợp.",
    "current_password" => ":attribute không đúng.",
    "date" => ":attribute sai định dạng ngày.",
    "date_equals" => ":attribute phải là một ngày bằng :date",
    "date_format" => ":attribute không phù hợp với định dạng :format",
    "declined" => ":attribute phải bị từ chối.",
    "declined_if" => ":attribute phải bị từ chối khi :other là :value.",
    "different" => ":attribute và :other phải khác nhau.",
    "digits" => ":attribute phải là :digits.",
    "digits_between" => ":attribute phải ở giữa :min và :max.",
    "dimensions" => ":attribute có kích thước hình ảnh không hợp lệ.",
    "distinct" => ":attribute trường có giá trị trùng lặp.",
    "email" => ":attribute không phải là một Email hợp lệ",
    "ends_with" => ":attribute không phải là một URL hợp lệ",
    "enum" => ":attribute phải là một ngày sau :date",
    "exists" => ":attribute không hợp lệ",
    "file" => ":attribute phải là một tệp :date",
    "filled" => ":attribute phải là một ngày sau :date",
    "gt" => [
        "numeric" => "Vui lòng chọn :attribute",
        "file" => ":attribute phải là một ngày sau :date",
        "string" => ":attribute phải là một ngày sau :date",
        "array" => ":attribute phải là một ngày sau :date"
    ],
    "gte" => [
        "numeric" => ":attribute phải là một ngày sau :date",
        "file" => ":attribute phải là một ngày sau :date",
        "string" => ":attribute phải là một ngày sau :date",
        "array" => "The  :thuộc tính phải là ngày sau hoặc bằng :date"
    ],
    "image" => ":attribute phải là một hình ảnh.",
    "in" => ":attribute được chọn không hợp lệ.",
    "in_array" => ":attribute không tồn tại trong: other.",
    "integer" => ":attribute phải là một số nguyên.",
    "ip" => ":attribute phải là một giá trị hợp lệ Địa chỉ IP.",
    "ipv4" => ":attribute phải là địa chỉ IPv4 hợp lệ.",
    "ipv6" => ":attribute phải là địa chỉ IPv6 hợp lệ.",
    "json" => ":attribute phải là một chuỗi JSON hợp lệ.",
    "lt" => [
        'numeric' => ':attribute phải nhỏ hơn :value.',
        'file' => ':attribute phải nhỏ hơn :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn :value characters.',
        'array' => ':attribute phải có ít hơn :value items.'
    ],
    "lte" => [
        'numeric' => ':attribute phải nhỏ hơn hoặc bằng to :value.',
        'file' => ':attribute phải nhỏ hơn hoặc bằng to :value kilobytes.',
        'string' => ':attribute phải nhỏ hơn hoặc bằng to :value characters.',
        'array' => ':attribute không được có nhiều hơn :value items.'
    ],
    "mac_address" => ":attribute phải là một địa chỉ MAC hợp lệ",
    "max" => [
        "numeric" => ":attribute không được lớn hơn :max",
        "file" => ":attribute không được lớn hơn :max",
        "string" => ":attribute không được lớn hơn :max",
        "array" => ":attributekhông được có nhiều hơn :max giá trị"
    ],
    "mimes" => ":attribute không tồn tại trong: other",
    "mimetypes" => ":attribute không tồn tại trong: other",
    "min" => [
        "numeric" => ":attribute ít nhất phải là :min",
        "file" => ":attribute ít nhất phải là :min",
        "string" => ":attribute ít nhất phải là :min ký tự",
        "array" => ":attribute phải có ít nhất :min giá trị"
    ],
    "multiple_of" => ":attribute phải là bội số của :value.",
    "not_in" => ":attribute đã chọn không hợp lệ.",
    "not_regex" => "Định dạng :attribute không hợp lệ.",
    "numeric" => ":attribute phải là một số.",
    "password" => "Mật khẩu không chính xác.",
    "present" => ":attribute phải có trường thuộc tính.",
    "prohibited" => ":attribute bị cấm.",
    "prohibited_if" => ":attribute bị cấm khi: khác là: giá trị.",
    "prohibited_unless" => ":attribute bị cấm trừ khi: khác nằm trong: giá trị.",
    "prohibits" => " : trường thuộc tính cấm: khác không có mặt.",
    "regex" => "Định dạng :attribute không hợp lệ.",
    "required" => "Vui lòng nhập :attribute",
    "required_array_keys" => ":attribute trường phải chứa các mục nhập cho :values",
    "required_if" => ":attribute là bắt buộc khi :other có :value",
    "required_unless" => ":attribute là bắt buộc trừ khi :other có :values",
    "required_with" => ":attribute là bắt buộc khi có :values",
    "required_with_all" => ":attribute là bắt buộc khi có những giá trị :values",
    "required_without" => ":attributetrường là bắt buộc khi không có :values.",
    "required_without_all" => ":attribute là bắt buộc khi không có giá trị nào trong số :values",
    "same" => ":attribute và :other không khớp",
    "size" => [
        "numeric" => ":attribute phải chứa :size",
        "file" => ":attribute phải chứa :size tệp",
        "string" => ":attribute phải chứa :size ký tự",
        "array" => ":attribute phải chứa :size giá trị"
    ],
    "starts_with" => ":attribute phải bắt đầu bằng một trong những điều sau :values",
    "string" => ":attribute phải là một chuỗi",
    "timezone" => ":attribute phải là múi giờ hợp lệ.",
    "unique" => ":attribute đã tồn tại.",
    "uploaded" => ":attribute không tải lên được",
    "url" => "Định dạng URL không hợp lệ",
    "uuid" => "Định dạng UUID không hợp lệ",
    "invalid_date" => ":attribute không hợp lệ. Vui lòng đặt thời gian sau hiện tại 1 ngày",
    "date_required" => "Vui lòng chọn :attribute",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'money-invalid' => 'Số tiền thanh toán không hợp lệ.'
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'fullname' => 'họ tên',
        'email' => 'email',
        'content' => 'nội dung'
    ],

];
