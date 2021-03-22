<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-03-20 19:52:34 --> Query error: Unknown column 'box_quantity' in 'field list' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS packing_type, box_quantity, box_type, l_size, w_size, h_size, volume, pack_price, stock_qty ,tblpack_list.id
    FROM tblpack_list
    
    
    
    
    ORDER BY packing_type ASC
    LIMIT 0, 25
    
ERROR - 2021-03-20 19:52:46 --> Query error: Unknown column 'box_quantity' in 'field list' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS packing_type, box_quantity, box_type, l_size, w_size, h_size, volume, pack_price, stock_qty ,tblpack_list.id
    FROM tblpack_list
    
    
    
    
    ORDER BY packing_type ASC
    LIMIT 0, 25
    
ERROR - 2021-03-20 19:53:01 --> Query error: Unknown column 'box_quantity' in 'field list' - Invalid query: 
    SELECT SQL_CALC_FOUND_ROWS packing_type, box_quantity, box_type, l_size, w_size, h_size, volume, pack_price, stock_qty ,tblpack_list.id
    FROM tblpack_list
    
    
    
    
    ORDER BY packing_type ASC
    LIMIT 0, 25
    
ERROR - 2021-03-20 19:59:22 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'WHERE `p_qty_id` = 99
AND `rel_wo_id` IS NULL' at line 5 - Invalid query: SELECT *
FROM `tblproduced_qty`
LEFT JOIN `tblevents` ON `tblevents`.`eventid`=`tblproduced_qty`.`rel_event_id`
LEFT JOIN `tblplan_recipe` ON `tblplan_recipe`.`connected_pair`=
WHERE `p_qty_id` = 99
AND `rel_wo_id` IS NULL
