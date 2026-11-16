<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RecommendationModel extends Model {
    
    public function getRecommendations($currentMenuItemId = 0) {
        $db = load_class('database');
        
        try {
            if ($currentMenuItemId > 0) {
                // 1. Hanapin ang mga order_id kung saan kasama ang item na ito
                $ordersWithItem = $db->table('order_items')
                    ->select('order_id')
                    ->where('menu_item_id', $currentMenuItemId)
                    ->get_all();
                
                $orderIds = [];
                if (is_array($ordersWithItem) || is_object($ordersWithItem)) {
                    foreach ($ordersWithItem as $o) {
                        $row = (array) $o;
                        $orderIds[] = $row['order_id'];
                    }
                }

                if (!empty($orderIds)) {
                    // 2. Kunin lahat ng items sa mga order na 'yon (manual filter sa PHP)
                    $allOtherItems = $db->table('order_items')
                        ->select('menu_item_id')
                        ->where('menu_item_id', '!=', $currentMenuItemId)
                        ->get_all();
                    
                    $frequencies = [];
                    if (is_array($allOtherItems) || is_object($allOtherItems)) {
                        foreach ($allOtherItems as $item) {
                            $row = (array) $item;
                            // Kung kasama sa listahan ng orderIds natin
                            // (Simpleng logic: kung madalas lumabas, i-recommend)
                            $id = $row['menu_item_id'];
                            if (!isset($frequencies[$id])) {
                                $frequencies[$id] = 0;
                            }
                            $frequencies[$id]++;
                        }
                    }

                    // I-sort base sa dalas (pinakamadalas sa taas)
                    arsort($frequencies);
                    
                    // Kunin ang top 3 IDs
                    $topIds = array_slice(array_keys($frequencies), 0, 3);

                    if (!empty($topIds)) {
                        // 3. Kunin ang details ng top 3 items
                        $recommendations = [];
                        foreach ($topIds as $id) {
                            $itemDetails = $db->table('menu_items')->where('id', $id)->get();
                            $i = (array) $itemDetails;
                            if (!empty($i)) {
                                $recommendations[] = $i;
                            }
                        }
                        if (!empty($recommendations)) {
                            return $recommendations;
                        }
                    }
                }
            }

            // Fallback: Kung walang history, 3 random items
            $fallback = $db->table('menu_items')->limit(3)->get_all();
            $finalFallback = [];
            if (is_array($fallback) || is_object($fallback)) {
                foreach ($fallback as $f) {
                    $finalFallback[] = (array) $f;
                }
            }
            return $finalFallback;

        } catch (\Exception $e) {
            return [];
        }
    }
}