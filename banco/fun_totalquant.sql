CREATE FUNCTION fun_totalsku (in_codproduto INTEGER)
RETURNS INTEGER
BEGIN
    DECLARE soma INTEGER;
    
    SELECT
        SUM(quantidade)
    INTO
        soma
    FROM
        sku
    WHERE
        codproduto = in_codproduto;
    
    RETURN soma;
END
//