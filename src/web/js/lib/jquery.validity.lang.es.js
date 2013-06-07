$.extend($.validity.messages, {
    require:"#{field} es oblitario.",
    // Format validators:
    match:"#{field} está en el formato correcto.",
    integer:"#{field} debe ser un número entero positivo.",
    date:"#{field} debe ser una fecha.",
    email:"#{field} debe ser una dirección de email.",
    usd:"#{field} debe ser una cantidad en dólares estadounidenses.",
    url:"#{field} debe ser una dirección URL.",
    number:"#{field} debe ser un nombre.",
    zip:"#{field} debe ser un código postal #####.",
    phone:"#{field} debe ser un número de téléfono ###-###-####.",
    guid:"#{field} debe ser un guid con el formato {3F2504E0-4F89-11D3-9A0C-0305E82C3301}.",
    time24:"#{field} debe ser una hora con el formato en 24 horas (ej: 23:00).",
    time12:"#{field} debe ser una hora con el formato en 12 horas (ej:12:00 AM/PM)",
    // Value range messages:
    lessThan:"#{field} debe ser menor que #{max}.",
    lessThanOrEqualTo:"#{field} debe ser menor o igual que #{max}.",
    greaterThan:"#{field} debe ser mayor que #{min}.",
    greaterThanOrEqualTo:"#{field} debe ser mayor o igual que #{min}.",
    range:"#{field} debe estar comprendido entre #{min} y #{max}.",
    // Value length messages:
    tooLong:"#{field} no debe tener más de #{max} caracteres.",
    tooShort:"#{field} no debe tener menos de #{min} caracteres.",
    // Composition validators:
    nonHtml:"#{field} no debe contener caracteres HTML.",
    alphabet:"#{field} contiene caracteres no permitidos.",
    minCharClass:"#{field} no debe tener más de #{min} #{charClass} caracteres.",
    maxCharClass:"#{field} ne debe tener menos de #{min} #{charClass} caracteres.",
    // Aggregate validator messages:
    equal:"Los valores no coinciden.",
    distinct:"Un valor se repite.",
    sum:"La suma de los valores difiere de #{sum}.",
    sumMax:"La suma de los valores debe ser menor de #{max}.",
    sumMin:"La suma de los valores debe ser mayor de #{min}.",
    // Radio validator messages:
    radioChecked:"El valor seleccionado no es válido.",
    generic:"Invalido."
});

$.validity.setup({ defaultFieldName:"Este campo", });