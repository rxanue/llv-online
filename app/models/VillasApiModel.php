<?php

class VillasApiModel extends Model
{
    private $allowed_tables = [
        'rooms',
        'reservations',
        'food_categories',
        'menu_items',
        'food_orders',
        'order_items',
        'inventory_items',
        'inventory_movements',
        'dive_services',
        'dive_bookings',
        'dive_equipment',
        'dive_equipment_assignments',
        'payments',
        'user_settings'
    ];

    private function get_allowed_table($resource)
    {
        if (!is_string($resource)) {
            return false;
        }

        $resource = strtolower(trim($resource));

        if (!in_array($resource, $this->allowed_tables, true)) {
            return false;
        }

        return $resource;
    }

    public function all_records($resource, $limit = 100)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false) {
            return false;
        }

        $limit = max(1, min((int) $limit, 500));

        try {
            return $this->db
                ->table($table)
                ->order_by('id', 'DESC')
                ->limit($limit)
                ->get_all();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function find_record($resource, $id)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false) {
            return false;
        }

        try {
            return $this->db
                ->table($table)
                ->where('id', (int) $id)
                ->get();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function create_record($resource, $data)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false || !is_array($data) || empty($data)) {
            return false;
        }

        // Do not allow clients to provide the primary key.
        unset($data['id']);

        try {
            $result = $this->db
                ->table($table)
                ->insert($data);

            if ($result === false) {
                return false;
            }

            return $result;
        } catch (Throwable $e) {
            return false;
        }
    }

    public function update_record($resource, $id, $data)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false || !is_array($data) || empty($data)) {
            return false;
        }

        unset($data['id']);

        try {
            $existing = $this->db
                ->table($table)
                ->where('id', (int) $id)
                ->get();

            if (empty($existing)) {
                return false;
            }

            return $this->db
                ->table($table)
                ->where('id', (int) $id)
                ->update($data);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function delete_record($resource, $id)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false) {
            return false;
        }

        try {
            $existing = $this->db
                ->table($table)
                ->where('id', (int) $id)
                ->get();

            if (empty($existing)) {
                return false;
            }

            return $this->db
                ->table($table)
                ->where('id', (int) $id)
                ->delete();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function dashboard_summary()
    {
        $tables = [
            'rooms',
            'reservations',
            'menu_items',
            'food_orders',
            'inventory_items',
            'dive_services',
            'dive_bookings',
            'dive_equipment',
            'payments'
        ];

        $summary = [];

        foreach ($tables as $table) {
            $summary[$table] = $this->count_records($table);
        }

        return $summary;
    }

    private function count_records($resource)
    {
        $table = $this->get_allowed_table($resource);

        if ($table === false) {
            return 0;
        }

        try {
            $records = $this->db
                ->table($table)
                ->get_all();

            return is_array($records) ? count($records) : 0;
        } catch (Throwable $e) {
            return 0;
        }
    }
}