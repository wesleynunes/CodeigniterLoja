DELIMITER //
CREATE TRIGGER trg_atualizaordemvitrine BEFORE INSERT ON vitrineproduto
FOR EACH ROW
BEGIN
	DECLARE v_novaposicao INT DEFAULT 0;
	SELECT
		IFNULL(MAX(ordemvitrineproduto),0)
	INTO
		v_novaposicao
	FROM
		vitrineproduto
	WHERE
		codvitrine = NEW.codvitrine;

	SET v_novaposicao = v_novaposicao + 1;

	SET NEW.ordemvitrineproduto = v_novaposicao;
END//