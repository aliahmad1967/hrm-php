<?php
/**
 * Dashboard Controller
 */

namespace App\Controllers;

class DashboardController extends Controller {
    
    public function index() {
        $db = $this->db;
        
        // Get statistics
        $stats = [
            'total_employees' => $db->query("SELECT COUNT(*) as count FROM employees WHERE status = 'active'")->fetch()['count'],
            'today_attendance' => $db->query("SELECT COUNT(*) as count FROM attendance WHERE date = CURDATE() AND status IN ('present', 'late')")->fetch()['count'],
            'pending_leaves' => $db->query("SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'")->fetch()['count'],
            'active_contracts' => $db->query("SELECT COUNT(*) as count FROM employees WHERE status = 'active' AND (contract_end_date IS NULL OR contract_end_date > DATE_ADD(CURDATE(), INTERVAL 30 DAY))")->fetch()['count']
        ];
        
        // Get today's attendance summary
        $todayAttendance = $db->query(
            "SELECT status, COUNT(*) as count 
             FROM attendance 
             WHERE date = CURDATE() 
             GROUP BY status"
        )->fetchAll();
        
        $attendanceSummary = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'leave' => 0
        ];
        
        foreach ($todayAttendance as $att) {
            $attendanceSummary[$att['status']] = $att['count'];
        }
        
        // Get department distribution
        $departmentStats = $db->query(
            "SELECT d.name_ar, COUNT(e.id) as count 
             FROM departments d 
             LEFT JOIN employees e ON d.id = e.department_id AND e.status = 'active'
             GROUP BY d.id, d.name_ar"
        )->fetchAll();
        
        // Get pending leaves
        $pendingLeaves = $db->query(
            "SELECT lr.*, e.full_name as employee_name, e.photo 
             FROM leave_requests lr 
             JOIN employees e ON lr.employee_id = e.id 
             WHERE lr.status = 'pending' 
             ORDER BY lr.created_at DESC 
             LIMIT 5"
        )->fetchAll();
        
        // Get upcoming contract endings
        $upcomingContracts = $db->query(
            "SELECT e.*, d.name_ar as department_name 
             FROM employees e 
             LEFT JOIN departments d ON e.department_id = d.id 
             WHERE e.status = 'active' 
             AND e.contract_end_date IS NOT NULL 
             AND e.contract_end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
             ORDER BY e.contract_end_date ASC 
             LIMIT 5"
        )->fetchAll();
        
        // Get recent employees
        $recentEmployees = $db->query(
            "SELECT e.*, d.name_ar as department_name 
             FROM employees e 
             LEFT JOIN departments d ON e.department_id = d.id 
             ORDER BY e.created_at DESC 
             LIMIT 5"
        )->fetchAll();
        
        // Get attendance trend (last 7 days)
        $attendanceTrend = $db->query(
            "SELECT date, 
                    SUM(CASE WHEN status IN ('present', 'late') THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
             FROM attendance 
             WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
             GROUP BY date 
             ORDER BY date ASC"
        )->fetchAll();
        
        $this->view('dashboard/index', [
            'stats' => $stats,
            'attendanceSummary' => $attendanceSummary,
            'departmentStats' => $departmentStats,
            'pendingLeaves' => $pendingLeaves,
            'upcomingContracts' => $upcomingContracts,
            'recentEmployees' => $recentEmployees,
            'attendanceTrend' => $attendanceTrend,
            'title' => 'لوحة التحكم'
        ]);
    }
}