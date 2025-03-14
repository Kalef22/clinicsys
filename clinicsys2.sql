-- Se insertarán todas las fechas desde la fecha actual hasta el 31 de diciembre de 2040
DELIMITER $$

CREATE PROCEDURE InsertarFechas()
BEGIN
    DECLARE fecha_actual DATE;
    DECLARE fecha_final DATE;

    SET fecha_actual = CURDATE(); -- Fecha de hoy
    SET fecha_final = '2040-12-31'; -- Última fecha a insertar

    -- Preparar la consulta de inserción
    PREPARE stmt FROM 'INSERT INTO calendario (fecha) VALUES (?)';

    -- Bucle para insertar cada día hasta 2040
    WHILE fecha_actual <= fecha_final DO
        EXECUTE stmt USING fecha_actual;
        SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 DAY);
    END WHILE;

    -- Liberar memoria
    DEALLOCATE PREPARE stmt;
END $$

DELIMITER ;

CALL InsertarFechas();

DROP PROCEDURE InsertarFechas;
