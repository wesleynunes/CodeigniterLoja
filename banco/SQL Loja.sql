-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema lojacodeigniter
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema lojacodeigniter
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `lojacodeigniter` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `lojacodeigniter` ;

-- -----------------------------------------------------
-- Table `lojacodeigniter`.`configuracao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`configuracao` (
  `codconfiguracao` INT NOT NULL AUTO_INCREMENT,
  `nomeloja` VARCHAR(100) NOT NULL,
  `enderecoloja` VARCHAR(100) NOT NULL,
  `cnpjloja` VARCHAR(14) NOT NULL,
  `emailloja` VARCHAR(100) NOT NULL,
  `cidadeloja` VARCHAR(45) NOT NULL,
  `ufloja` CHAR(2) NOT NULL,
  PRIMARY KEY (`codconfiguracao`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`comprador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`comprador` (
  `codcomprador` INT NOT NULL AUTO_INCREMENT COMMENT 'Armazena os clientes da loja.',
  `nomecomprador` VARCHAR(100) NOT NULL,
  `enderecocomprador` VARCHAR(100) NOT NULL,
  `cidadecomprador` VARCHAR(100) NOT NULL,
  `ufcomprador` CHAR(2) NOT NULL,
  `cepcomprador` VARCHAR(8) NOT NULL,
  `emailcomprador` VARCHAR(100) NOT NULL,
  `telefonecomprador` VARCHAR(100) NULL,
  `cpfcomprador` VARCHAR(11) NOT NULL,
  `sexocomprador` CHAR(2) NOT NULL,
  `senhacomprador` VARCHAR(300) NOT NULL,
  PRIMARY KEY (`codcomprador`),
  UNIQUE INDEX `emailcomprador_UNIQUE` (`emailcomprador` ASC),
  UNIQUE INDEX `cpfcomprador_UNIQUE` (`cpfcomprador` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`usuario` (
  `codusuario` INT NOT NULL AUTO_INCREMENT COMMENT 'Armazena os usuaários do painel.',
  `nomeusuario` VARCHAR(100) NOT NULL,
  `emailusuario` VARCHAR(100) NOT NULL,
  `senhausuario` VARCHAR(300) NOT NULL,
  `ativadousuario` CHAR(1) NOT NULL,
  PRIMARY KEY (`codusuario`),
  UNIQUE INDEX `emailusuario_UNIQUE` (`emailusuario` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`departamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`departamento` (
  `codepartamento` INT NOT NULL AUTO_INCREMENT COMMENT 'Departamento do produto',
  `nomedepartamento` VARCHAR(45) NOT NULL,
  `coddepartamentopai` INT NULL,
  PRIMARY KEY (`codepartamento`),
  INDEX `fk_produtodepartamento_produtodepartamento_idx` (`coddepartamentopai` ASC),
  CONSTRAINT `fk_produtodepartamento_produtodepartamento`
    FOREIGN KEY (`coddepartamentopai`)
    REFERENCES `lojacodeigniter`.`departamento` (`codepartamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`tipoatributo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`tipoatributo` (
  `codtipoatributo` INT NOT NULL AUTO_INCREMENT,
  `nometipoatributo` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`codtipoatributo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`produto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`produto` (
  `codproduto` INT NOT NULL AUTO_INCREMENT,
  `nomeproduto` VARCHAR(100) NOT NULL,
  `resumoproduto` TEXT NOT NULL,
  `fichaproduto` TEXT NOT NULL,
  `valorproduto` DECIMAL(10,2) NOT NULL,
  `valorpromocional` DECIMAL(10,2) NULL COMMENT 'Armazena o valor promocional de produto.',
  `codtipoatributo` INT NULL COMMENT 'Armazena a sua classificação',
  `urlseo` VARCHAR(120) NOT NULL COMMENT 'URL para SEO',
  `peso` DECIMAL(10,3) NULL,
  `altura` DECIMAL(10,3) NULL,
  `largura` DECIMAL(10,3) NULL,
  `comprimento` DECIMAL(10,3) NULL,
  PRIMARY KEY (`codproduto`),
  INDEX `fk_produto_tipoatributo1_idx` (`codtipoatributo` ASC),
  CONSTRAINT `fk_produto_tipoatributo1`
    FOREIGN KEY (`codtipoatributo`)
    REFERENCES `lojacodeigniter`.`tipoatributo` (`codtipoatributo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`produtodepartamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`produtodepartamento` (
  `codproduto` INT NOT NULL,
  `codprodutodepartamento` INT NOT NULL,
  PRIMARY KEY (`codproduto`, `codprodutodepartamento`),
  INDEX `fk_produto_has_produtodepartamento_produtodepartamento1_idx` (`codprodutodepartamento` ASC),
  INDEX `fk_produto_has_produtodepartamento_produto1_idx` (`codproduto` ASC),
  CONSTRAINT `fk_produto_has_produtodepartamento_produto1`
    FOREIGN KEY (`codproduto`)
    REFERENCES `lojacodeigniter`.`produto` (`codproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_produto_has_produtodepartamento_produtodepartamento1`
    FOREIGN KEY (`codprodutodepartamento`)
    REFERENCES `lojacodeigniter`.`departamento` (`codepartamento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`atributo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`atributo` (
  `codatributo` INT NOT NULL AUTO_INCREMENT,
  `nomeatributo` VARCHAR(45) NOT NULL,
  `codtipoatributo` INT NOT NULL,
  PRIMARY KEY (`codatributo`),
  INDEX `fk_atributo_tipoatributo1_idx` (`codtipoatributo` ASC),
  CONSTRAINT `fk_atributo_tipoatributo1`
    FOREIGN KEY (`codtipoatributo`)
    REFERENCES `lojacodeigniter`.`tipoatributo` (`codtipoatributo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`formaentrega`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`formaentrega` (
  `codformaentrega` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da forma de entrega',
  `nomeformaentrega` VARCHAR(100) NOT NULL COMMENT 'Nome da forma de entrega',
  `habilitaformaentrega` CHAR(1) NOT NULL DEFAULT 'S' COMMENT 'Define se a forma de entrega está habilitada',
  `codigocorreiosformaentrega` VARCHAR(45) NULL COMMENT 'Código para webservice dos Correios',
  PRIMARY KEY (`codformaentrega`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`formapagamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`formapagamento` (
  `codformapagamento` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da forma de pagamento',
  `nomeformapagamento` VARCHAR(100) NOT NULL COMMENT 'Armazena o nome da forma de pagamento',
  `tipoformapagamento` CHAR(1) NULL COMMENT 'Armazena o tipo da forma de pagamento:\n1 - Boleto\n2 - Cartão de Crédito',
  `descontoformapagamento` DECIMAL(10,2) NULL,
  `habilitaformapagamento` CHAR(1) NULL DEFAULT 'S' COMMENT 'Habilita a forma de pagamento.',
  `maximoparcelasformapagamento` INT NOT NULL DEFAULT 1 COMMENT 'Armazena o máximo de parcelas para este modo de pagamento.',
  PRIMARY KEY (`codformapagamento`),
  UNIQUE INDEX `nomeformapagamento_UNIQUE` (`nomeformapagamento` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`carrinho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`carrinho` (
  `codcarrinho` INT ZEROFILL NOT NULL AUTO_INCREMENT,
  `datahoracompra` TIMESTAMP NOT NULL,
  `valorcompra` DECIMAL(10,2) NOT NULL,
  `valorfrete` DECIMAL(10,2) NULL,
  `valorfinalcompra` DECIMAL(10,2) NOT NULL,
  `codcomprador` INT NOT NULL,
  `situacao` CHAR(1) NULL DEFAULT 'A' COMMENT 'Situação do Carrinho\nA-Aberto\nP-Pago\nE-Enviado\nC-Cancelado\nR-Recebido',
  `observacao` TEXT NULL,
  `codformaentrega` INT NOT NULL,
  `codformapagamento` INT NOT NULL,
  `enderecoentrega` VARCHAR(250) NULL,
  `cidadeentrega` VARCHAR(100) NULL,
  `ufentrega` CHAR(2) NULL,
  `cepentrega` INT NULL,
  PRIMARY KEY (`codcarrinho`),
  INDEX `fk_carrinho_comprador1_idx` (`codcomprador` ASC),
  INDEX `fk_carrinho_formaentrega1_idx` (`codformaentrega` ASC),
  INDEX `fk_carrinho_formapagamento1_idx` (`codformapagamento` ASC),
  CONSTRAINT `fk_carrinho_comprador1`
    FOREIGN KEY (`codcomprador`)
    REFERENCES `lojacodeigniter`.`comprador` (`codcomprador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_carrinho_formaentrega1`
    FOREIGN KEY (`codformaentrega`)
    REFERENCES `lojacodeigniter`.`formaentrega` (`codformaentrega`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_carrinho_formapagamento1`
    FOREIGN KEY (`codformapagamento`)
    REFERENCES `lojacodeigniter`.`formapagamento` (`codformapagamento`)
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`sku`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`sku` (
  `codsku` INT NOT NULL AUTO_INCREMENT COMMENT 'Código do SKU',
  `referencia` VARCHAR(150) NULL COMMENT 'Referência do SKU',
  `quantidade` INT NOT NULL DEFAULT 0 COMMENT 'Quantidade do produto em estoque',
  `codproduto` INT NOT NULL,
  PRIMARY KEY (`codsku`),
  INDEX `fk_sku_produto1_idx` (`codproduto` ASC),
  CONSTRAINT `fk_sku_produto1`
    FOREIGN KEY (`codproduto`)
    REFERENCES `lojacodeigniter`.`produto` (`codproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`itemcarrinho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`itemcarrinho` (
  `coditemcarrinho` INT NOT NULL AUTO_INCREMENT,
  `valoritem` DECIMAL(10,2) NOT NULL,
  `quantidadeitem` INT NOT NULL,
  `codcarrinho` INT ZEROFILL NOT NULL,
  `codsku` INT NOT NULL,
  PRIMARY KEY (`coditemcarrinho`),
  INDEX `fk_itemcarrinho_carrinho1_idx` (`codcarrinho` ASC),
  INDEX `fk_itemcarrinho_sku1_idx` (`codsku` ASC),
  CONSTRAINT `fk_itemcarrinho_carrinho1`
    FOREIGN KEY (`codcarrinho`)
    REFERENCES `lojacodeigniter`.`carrinho` (`codcarrinho`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_itemcarrinho_sku1`
    FOREIGN KEY (`codsku`)
    REFERENCES `lojacodeigniter`.`sku` (`codsku`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`skuatributo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`skuatributo` (
  `codsku` INT NOT NULL COMMENT 'Código SKU',
  `codatributo` INT NOT NULL COMMENT 'Código do Atributo',
  PRIMARY KEY (`codsku`, `codatributo`),
  INDEX `fk_sku_has_atributo_atributo1_idx` (`codatributo` ASC),
  INDEX `fk_sku_has_atributo_sku1_idx` (`codsku` ASC),
  CONSTRAINT `fk_sku_has_atributo_sku1`
    FOREIGN KEY (`codsku`)
    REFERENCES `lojacodeigniter`.`sku` (`codsku`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_sku_has_atributo_atributo1`
    FOREIGN KEY (`codatributo`)
    REFERENCES `lojacodeigniter`.`atributo` (`codatributo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`produtofoto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`produtofoto` (
  `codprodutofoto` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da foto',
  `produtofotoextensao` VARCHAR(4) NOT NULL COMMENT 'Extensão do arquivo',
  `codproduto` INT NOT NULL,
  PRIMARY KEY (`codprodutofoto`),
  UNIQUE INDEX `codprodutofoto_UNIQUE` (`codprodutofoto` ASC),
  INDEX `fk_produtofoto_produto1_idx` (`codproduto` ASC),
  CONSTRAINT `fk_produtofoto_produto1`
    FOREIGN KEY (`codproduto`)
    REFERENCES `lojacodeigniter`.`produto` (`codproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`produtofotosku`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`produtofotosku` (
  `codprodutofotosku` INT NOT NULL AUTO_INCREMENT COMMENT 'Chave primária',
  `codprodutofoto` INT NOT NULL COMMENT 'Código da foto',
  `codsku` INT NOT NULL COMMENT 'Código do SKU',
  PRIMARY KEY (`codprodutofotosku`),
  INDEX `fk_produtofotosku_produtofoto1_idx` (`codprodutofoto` ASC),
  INDEX `fk_produtofotosku_sku1_idx` (`codsku` ASC),
  CONSTRAINT `fk_produtofotosku_produtofoto1`
    FOREIGN KEY (`codprodutofoto`)
    REFERENCES `lojacodeigniter`.`produtofoto` (`codprodutofoto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_produtofotosku_sku`
    FOREIGN KEY (`codsku`)
    REFERENCES `lojacodeigniter`.`sku` (`codsku`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`faixaprecoformaentrega`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`faixaprecoformaentrega` (
  `codfaixaprecoformaentrega` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da faixa de preço',
  `cepinicialfaixaprecoformaentrega` INT NOT NULL COMMENT 'Início da faixa de cep para entrega',
  `cepfinalfaixaprecoformaentrega` INT NOT NULL COMMENT 'Fim da faixa de cep para entrega',
  `pesoinicialfaixaprecoformaentrega` DECIMAL(10,3) NOT NULL COMMENT 'Início da faixa de peso para entrega',
  `pesofinalfaixaprecoformaentrega` DECIMAL(10,3) NOT NULL COMMENT 'Fim da faixa de peso para entrega',
  `valorfaixaprecoformaentrega` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Valor da entrega',
  `codformaentrega` INT NOT NULL,
  `prazofaixaprecoformaentrega` INT NULL,
  PRIMARY KEY (`codfaixaprecoformaentrega`),
  INDEX `fk_faixaprecoformaentrega_formaentrega1_idx` (`codformaentrega` ASC),
  CONSTRAINT `fk_formaentrega_faixaprecoformaentrega`
    FOREIGN KEY (`codformaentrega`)
    REFERENCES `lojacodeigniter`.`formaentrega` (`codformaentrega`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`faixacepfretegratis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`faixacepfretegratis` (
  `codfaixacepfretegratis` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da faixa de cep',
  `cepinicialfaixacepfretegratis` INT NOT NULL COMMENT 'Faixa inicial para o frete grátis',
  `cepfinalfaixacepfretegratis` INT NOT NULL COMMENT 'Faixa final do cep grátis',
  `pesoinicialfaixacepfretegratis` DECIMAL(10,3) NULL COMMENT 'Peso inicial para o frete grátis',
  `pesofinalfaixacepfretegratis` DECIMAL(10,3) NULL COMMENT 'Peso final para frete grátis',
  `valorminimofaixacepfretegratis` DECIMAL(10,2) NULL DEFAULT 0 COMMENT 'Valor mínimo para ser um frete grátis',
  `habilitafaixacepfretegratis` CHAR(1) NOT NULL DEFAULT 'S' COMMENT 'Define se o frete grátis está habilitado. S-Sim\n\nN-Não',
  `codformaentrega` INT NOT NULL,
  INDEX `fk_faixacepfretegratis_formaentrega1_idx` (`codformaentrega` ASC),
  PRIMARY KEY (`codfaixacepfretegratis`),
  CONSTRAINT `fk_faixacepfretegratis_formaentrega1`
    FOREIGN KEY (`codformaentrega`)
    REFERENCES `lojacodeigniter`.`formaentrega` (`codformaentrega`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`vitrine`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`vitrine` (
  `codvitrine` INT NOT NULL AUTO_INCREMENT COMMENT 'Código da vitrine',
  `nomevitrine` VARCHAR(100) NOT NULL COMMENT 'Nome da vitrine',
  `vitrineativa` CHAR(1) NOT NULL DEFAULT 'A' COMMENT 'Define se a vitrine está ativa. S-Sim, N-Não',
  `datainiciovitrine` DATE NULL COMMENT 'Data de início de exibição da vitrine.',
  `datafinalvitrine` DATE NULL COMMENT 'Data final de exibição da vitrine.',
  PRIMARY KEY (`codvitrine`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lojacodeigniter`.`vitrineproduto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lojacodeigniter`.`vitrineproduto` (
  `codvitrineproduto` INT NOT NULL AUTO_INCREMENT COMMENT 'Código do item',
  `ordemvitrineproduto` INT NOT NULL COMMENT 'Ordem do produto na vitrine.',
  `codproduto` INT NOT NULL COMMENT 'Código do produto',
  `codvitrine` INT NOT NULL COMMENT 'Código da vitrine',
  PRIMARY KEY (`codvitrineproduto`, `codproduto`, `codvitrine`),
  INDEX `fk_vitrineproduto_produto1_idx` (`codproduto` ASC),
  INDEX `fk_vitrineproduto_vitrine1_idx` (`codvitrine` ASC),
  CONSTRAINT `fk_vitrineproduto_produto1`
    FOREIGN KEY (`codproduto`)
    REFERENCES `lojacodeigniter`.`produto` (`codproduto`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_vitrineproduto_vitrine1`
    FOREIGN KEY (`codvitrine`)
    REFERENCES `lojacodeigniter`.`vitrine` (`codvitrine`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
