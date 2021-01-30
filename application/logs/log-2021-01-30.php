<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-01-30 07:28:14 --> Query error: Unknown column 'S.AL' in 'where clause' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS tblstock_lists.product_code as p_code, `tbltransfer_lists`.`updated_at` AS `tbltransfer_lists.updated_at`, w1.warehouse_name as t_from, w2.warehouse_name as t_to, transaction_notes, transaction_qty, description, staff1.firstname as c_firstname, staff2.firstname as u_firstname ,tbltransfer_lists.id,staff1.lastname as c_lastname,staff2.lastname as u_lastname,stock_product_code,transaction_from,created_user,updated_user
    FROM tbltransfer_lists
    LEFT JOIN tblstock_lists ON tblstock_lists.id = tbltransfer_lists.stock_product_code LEFT JOIN tblwarehouses w1 ON w1.id = tbltransfer_lists.transaction_from LEFT JOIN tblwarehouses w2 ON w2.id = tbltransfer_lists.transaction_to LEFT JOIN tblstaff staff1 ON staff1.staffid = tbltransfer_lists.created_user LEFT JOIN tblstaff staff2 ON staff2.staffid = tbltransfer_lists.updated_user
    
    WHERE  tbltransfer_lists.allocation != 1 AND ( tbltransfer_lists.transaction_notes IN (S.AL-157, S.AL-157, S.AL-157))
    
    ORDER BY tblstock_lists.product_code ASC
    LIMIT 0, 25
    
ERROR - 2021-01-30 07:34:38 --> Query error: Unknown column 'S.AL' in 'where clause' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS tblstock_lists.product_code as p_code, `tbltransfer_lists`.`updated_at` AS `tbltransfer_lists.updated_at`, w1.warehouse_name as t_from, w2.warehouse_name as t_to, transaction_notes, transaction_qty, description, staff1.firstname as c_firstname, staff2.firstname as u_firstname ,tbltransfer_lists.id,staff1.lastname as c_lastname,staff2.lastname as u_lastname,stock_product_code,transaction_from,created_user,updated_user
    FROM tbltransfer_lists
    LEFT JOIN tblstock_lists ON tblstock_lists.id = tbltransfer_lists.stock_product_code LEFT JOIN tblwarehouses w1 ON w1.id = tbltransfer_lists.transaction_from LEFT JOIN tblwarehouses w2 ON w2.id = tbltransfer_lists.transaction_to LEFT JOIN tblstaff staff1 ON staff1.staffid = tbltransfer_lists.created_user LEFT JOIN tblstaff staff2 ON staff2.staffid = tbltransfer_lists.updated_user
    
    WHERE  tbltransfer_lists.allocation != 1 AND ( tbltransfer_lists.transaction_notes IN (S.AL-157, S.AL-157, S.AL-157))
    
    ORDER BY tblstock_lists.product_code ASC
    LIMIT 0, 25
    
ERROR - 2021-01-30 07:36:38 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'Stock in, Purchase Stock in))
    
    ORDER BY tblstock_lists.product_code ASC
' at line 5 - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS tblstock_lists.product_code as p_code, `tbltransfer_lists`.`updated_at` AS `tbltransfer_lists.updated_at`, w1.warehouse_name as t_from, w2.warehouse_name as t_to, transaction_notes, transaction_qty, description, staff1.firstname as c_firstname, staff2.firstname as u_firstname ,tbltransfer_lists.id,staff1.lastname as c_lastname,staff2.lastname as u_lastname,stock_product_code,transaction_from,created_user,updated_user
    FROM tbltransfer_lists
    LEFT JOIN tblstock_lists ON tblstock_lists.id = tbltransfer_lists.stock_product_code LEFT JOIN tblwarehouses w1 ON w1.id = tbltransfer_lists.transaction_from LEFT JOIN tblwarehouses w2 ON w2.id = tbltransfer_lists.transaction_to LEFT JOIN tblstaff staff1 ON staff1.staffid = tbltransfer_lists.created_user LEFT JOIN tblstaff staff2 ON staff2.staffid = tbltransfer_lists.updated_user
    
    WHERE  tbltransfer_lists.allocation != 1 AND ( tbltransfer_lists.transaction_notes IN (Purchase Stock in, Purchase Stock in))
    
    ORDER BY tblstock_lists.product_code ASC
    LIMIT 0, 25
    
ERROR - 2021-01-30 07:39:07 --> Query error: Unknown column 'S.AL' in 'where clause' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS tblstock_lists.product_code as p_code, `tbltransfer_lists`.`updated_at` AS `tbltransfer_lists.updated_at`, w1.warehouse_name as t_from, w2.warehouse_name as t_to, transaction_notes, transaction_qty, description, staff1.firstname as c_firstname, staff2.firstname as u_firstname ,tbltransfer_lists.id,staff1.lastname as c_lastname,staff2.lastname as u_lastname,stock_product_code,transaction_from,created_user,updated_user
    FROM tbltransfer_lists
    LEFT JOIN tblstock_lists ON tblstock_lists.id = tbltransfer_lists.stock_product_code LEFT JOIN tblwarehouses w1 ON w1.id = tbltransfer_lists.transaction_from LEFT JOIN tblwarehouses w2 ON w2.id = tbltransfer_lists.transaction_to LEFT JOIN tblstaff staff1 ON staff1.staffid = tbltransfer_lists.created_user LEFT JOIN tblstaff staff2 ON staff2.staffid = tbltransfer_lists.updated_user
    
    WHERE  tbltransfer_lists.allocation != 1 AND ( tbltransfer_lists.transaction_notes IN (S.AL-157, S.AL-157, S.AL-157))
    
    ORDER BY tblstock_lists.product_code ASC
    LIMIT 0, 25
    
ERROR - 2021-01-30 08:21:46 --> Query error: Unknown column 'S.AL' in 'where clause' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS tblstock_lists.product_code as p_code, `tbltransfer_lists`.`updated_at` AS `tbltransfer_lists.updated_at`, w1.warehouse_name as t_from, w2.warehouse_name as t_to, transaction_notes, transaction_qty, description, staff1.firstname as c_firstname, staff2.firstname as u_firstname ,tbltransfer_lists.id,staff1.lastname as c_lastname,staff2.lastname as u_lastname,stock_product_code,transaction_from,created_user,updated_user
    FROM tbltransfer_lists
    LEFT JOIN tblstock_lists ON tblstock_lists.id = tbltransfer_lists.stock_product_code LEFT JOIN tblwarehouses w1 ON w1.id = tbltransfer_lists.transaction_from LEFT JOIN tblwarehouses w2 ON w2.id = tbltransfer_lists.transaction_to LEFT JOIN tblstaff staff1 ON staff1.staffid = tbltransfer_lists.created_user LEFT JOIN tblstaff staff2 ON staff2.staffid = tbltransfer_lists.updated_user
    
    WHERE  tbltransfer_lists.allocation != 1 AND ( tbltransfer_lists.transaction_notes IN (S.AL-157, S.AL-157, S.AL-157))
    
    ORDER BY tblstock_lists.product_code ASC
    LIMIT 0, 25
    
