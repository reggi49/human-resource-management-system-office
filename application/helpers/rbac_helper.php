<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * HRMS Matrix RBAC Helper
 */

if (!function_exists('is_admin_user')) {
    /**
     * Check if currently logged in user is SUPER ADMIN or ADMIN
     *
     * @return bool
     */
    function is_admin_user() {
        $CI =& get_instance();
        $user_type = strtoupper(trim((string)$CI->session->userdata('user_type')));
        return ($user_type === 'SUPER ADMIN' || $user_type === 'ADMIN');
    }
}

if (!function_exists('check_admin_access')) {
    /**
     * Guard function: Restrict backend admin controllers exclusively to SUPER ADMIN and ADMIN.
     * Regular employees or unauthenticated users will be redirected to employee portal.
     */
    function check_admin_access() {
        $CI =& get_instance();
        
        // 1. If not logged in, redirect to login
        if ($CI->session->userdata('user_login_access') != 1) {
            redirect('login');
            exit;
        }

        // 2. If logged in but NOT Super Admin or Admin, restrict and redirect to employee portal
        if (!is_admin_user()) {
            if ($CI->input->is_ajax_request()) {
                $CI->output
                   ->set_content_type('application/json')
                   ->set_status_header(403)
                   ->set_output(json_encode([
                       'status' => 403,
                       'success' => false,
                       'message' => 'Akses ditolak: Halaman admin hanya dapat diakses oleh Super Admin atau Admin.'
                   ]));
                exit;
            }
            $CI->session->set_flashdata('feedback', 'Akses ditolak: Halaman admin hanya dapat diakses oleh Super Admin atau Admin.');
            redirect('portal');
            exit;
        }
    }
}

if (!function_exists('has_permission')) {
    /**
     * Check if currently logged in user has a specific permission
     *
     * @param string $permission_slug
     * @return bool
     */
    function has_permission($permission_slug) {
        $CI =& get_instance();
        
        // Ensure user is logged in
        $user_id = $CI->session->userdata('user_login_id');
        $user_type = $CI->session->userdata('user_type');
        
        if (!$user_id) {
            return FALSE;
        }
        
        // Backward compatibility & master bypass for SUPER ADMIN
        if (strtoupper($user_type) === 'SUPER ADMIN') {
            return TRUE;
        }

        // Get permissions stored in session
        $permissions = $CI->session->userdata('user_permissions');
        
        if (!is_array($permissions)) {
            $permissions = load_user_permissions($user_id);
            $CI->session->set_userdata('user_permissions', $permissions);
        }
        
        return in_array($permission_slug, $permissions);
    }
}

if (!function_exists('check_permission')) {
    /**
     * Guard function: Redirects to Access Denied if user lacks permission
     *
     * @param string $permission_slug
     */
    function check_permission($permission_slug) {
        if (!has_permission($permission_slug)) {
            $CI =& get_instance();
            if ($CI->input->is_ajax_request()) {
                $CI->output
                   ->set_content_type('application/json')
                   ->set_status_header(403)
                   ->set_output(json_encode([
                       'status' => 403,
                       'success' => false,
                       'message' => 'Akses ditolak: Anda tidak memiliki izin (' . $permission_slug . ').'
                   ]));
                exit;
            } else {
                redirect('Login/access_denied', 'refresh');
            }
        }
    }
}

if (!function_exists('load_user_permissions')) {
    /**
     * Load permission slugs from DB for a given employee ID or system ID
     *
     * @param string|int $employee_id
     * @return array
     */
    function load_user_permissions($employee_id) {
        $CI =& get_instance();
        $CI->load->database();

        // 1. Get numerical employee id from string em_id if needed
        $emp = $CI->db->get_where('employee', array('em_id' => $employee_id))->row();
        if (!$emp) {
            $emp = $CI->db->get_where('employee', array('id' => $employee_id))->row();
        }

        if (!$emp) {
            return array();
        }

        // Fallback for legacy SUPER ADMIN or ADMIN role
        if (strtoupper($emp->em_role) === 'SUPER ADMIN') {
            $query = $CI->db->select('slug')->get('permissions');
            $perms = array();
            foreach ($query->result() as $row) {
                $perms[] = $row->slug;
            }
            return $perms;
        }

        // Fetch permissions via DB join
        $CI->db->select('p.slug');
        $CI->db->from('employee_roles er');
        $CI->db->join('role_permissions rp', 'rp.role_id = er.role_id');
        $CI->db->join('permissions p', 'p.id = rp.permission_id');
        $CI->db->where('er.employee_id', $emp->id);
        $query = $CI->db->get();

        $permissions = array();
        if ($query && $query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $permissions[] = $row->slug;
            }
        } else {
            // Default permissions for standard EMPLOYEE role if no explicit role assigned in employee_roles
            if (strtoupper($emp->em_role) === 'ADMIN') {
                $permissions = array('employee.view', 'attendance.view', 'leave.approve', 'leave.apply');
            } else {
                $permissions = array('leave.apply', 'attendance.view');
            }
        }

        return array_unique($permissions);
    }
}

if (!function_exists('get_user_scope')) {
    /**
     * Get access scope for an employee (SELF, SUBORDINATE, DEPARTMENT, ORGANIZATION)
     *
     * @param string|int $employee_id
     * @return string
     */
    function get_user_scope($employee_id) {
        $CI =& get_instance();
        $CI->load->database();

        $emp = $CI->db->get_where('employee', array('em_id' => $employee_id))->row();
        if (!$emp) {
            $emp = $CI->db->get_where('employee', array('id' => $employee_id))->row();
        }

        if (!$emp || strtoupper($emp->em_role) === 'SUPER ADMIN') {
            return 'ORGANIZATION';
        }

        $row = $CI->db->get_where('employee_roles', array('employee_id' => $emp->id))->row();
        if ($row) {
            return $row->scope;
        }

        return 'SELF';
    }
}
