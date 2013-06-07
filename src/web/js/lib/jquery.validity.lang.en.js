$.extend($.validity.messages, {
    require:"#{field} is required.",
    // Format validators:
    match:"#{field} has de right format.",
    integer:"#{field} must be a positive number.",
    date:"#{field} must be a date.",
    email:"#{field} must be an email address.",
    usd:"#{field} must be an amount in US Dollars.",
    url:"#{field} must be an URL address.",
    number:"#{field} must be a name.",
    zip:"#{field}must be azip code #####.",
    phone:"#{field} must be a phone number ###-###-####.",
    guid:"#{field} must be a guid with a format like {3F2504E0-4F89-11D3-9A0C-0305E82C3301}.",
    time24:"#{field} must be a date with 24 hours format (eg: 23:00).",
    time12:"#{field} must be a date with 12 hours format (eg:12:00 AM/PM)",
    // Value range messages:
    lessThan:"#{field} must be smaller than #{max}.",
    lessThanOrEqualTo:"#{field} must be larger or equat to #{max}.",
    greaterThan:"#{field} must be larger than #{min}.",
    greaterThanOrEqualTo:"#{field} must be smaller or equal to #{min}.",
    range:"#{field} must be a value between #{min} and #{max}.",
    // Value length messages:
    tooLong:"#{field} must not have more than #{max} characters.",
    tooShort:"#{field} must not have less than #{min} characters.",
    // Composition validators:
    nonHtml:"#{field} must not have HTML characters.",
    alphabet:"#{field} has disallowed chartacters.",
    minCharClass:"#{field} must not have more than #{min} #{charClass} characters.",
    maxCharClass:"#{field} must not have less than #{min} #{charClass} characters.",
    // Aggregate validator messages:
    equal:"Values mismatch.",
    distinct:"A value is repeated.",
    sum:"Sum of values is different than #{sum}.",
    sumMax:"Sum of values must be less than #{max}.",
    sumMin:"Sum of values must be more than #{min}.",
    // Radio validator messages:
    radioChecked:"Selected values is not valid.",
    generic:"Not valid."
});

$.validity.setup({ defaultFieldName:"Este campo", });