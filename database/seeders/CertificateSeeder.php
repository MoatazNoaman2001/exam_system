<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $certificates = [
            [
                'name' => 'PMP - Project Management Professional',
                'name_ar' => 'PMP - محترف إدارة المشاريع',
                'description' => 'The most important industry-recognized certification for project managers',
                'description_ar' => 'الشهادة الأكثر أهمية والمعترف بها في الصناعة لمديري المشاريع',
                'code' => 'PMP',
                'icon' => 'fas fa-certificate',
                'color' => '#3b82f6',
                'sort_order' => 1,
            ],
            [
                'name' => 'CAPM - Certified Associate in Project Management',
                'name_ar' => 'CAPM - شريك معتمد في إدارة المشاريع',
                'description' => 'Entry-level certification for project practitioners',
                'description_ar' => 'شهادة المستوى الأساسي لممارسي المشاريع',
                'code' => 'CAPM',
                'icon' => 'fas fa-star',
                'color' => '#10b981',
                'sort_order' => 2,
            ],
            [
                'name' => 'PMI-ACP - Agile Certified Practitioner',
                'name_ar' => 'PMI-ACP - ممارس رشيق معتمد',
                'description' => 'Certification for agile project management methodologies',
                'description_ar' => 'شهادة لمنهجيات إدارة المشاريع الرشيقة',
                'code' => 'PMI-ACP',
                'icon' => 'fas fa-rocket',
                'color' => '#f59e0b',
                'sort_order' => 3,
            ],
            [
                'name' => 'PMI-RMP - Risk Management Professional',
                'name_ar' => 'PMI-RMP - محترف إدارة المخاطر',
                'description' => 'Specialized certification in project risk management',
                'description_ar' => 'شهادة متخصصة في إدارة مخاطر المشاريع',
                'code' => 'PMI-RMP',
                'icon' => 'fas fa-shield-alt',
                'color' => '#ef4444',
                'sort_order' => 4,
            ],
            [
                'name' => 'PMI-PBA - Professional in Business Analysis',
                'name_ar' => 'PMI-PBA - محترف في تحليل الأعمال',
                'description' => 'Certification for business analysis professionals',
                'description_ar' => 'شهادة لمحترفي تحليل الأعمال',
                'code' => 'PMI-PBA',
                'icon' => 'fas fa-chart-line',
                'color' => '#8b5cf6',
                'sort_order' => 5,
            ],
            [
                'name' => 'PgMP - Program Management Professional',
                'name_ar' => 'PgMP - محترف إدارة البرامج',
                'description' => 'Advanced certification for program managers',
                'description_ar' => 'شهادة متقدمة لمديري البرامج',
                'code' => 'PgMP',
                'icon' => 'fas fa-layer-group',
                'color' => '#06b6d4',
                'sort_order' => 6,
            ],
            [
                'name' => 'PfMP - Portfolio Management Professional',
                'name_ar' => 'PfMP - محترف إدارة المحافظ',
                'description' => 'Certification for portfolio management professionals',
                'description_ar' => 'شهادة لمحترفي إدارة المحافظ',
                'code' => 'PfMP',
                'icon' => 'fas fa-briefcase',
                'color' => '#84cc16',
                'sort_order' => 7,
            ],
            [
                'name' => 'DASM - Disciplined Agile Scrum Master',
                'name_ar' => 'DASM - سكروم ماستر رشيق منضبط',
                'description' => 'Certification for disciplined agile scrum masters',
                'description_ar' => 'شهادة لسكروم ماسترز الرشيقين المنضبطين',
                'code' => 'DASM',
                'icon' => 'fas fa-users',
                'color' => '#f97316',
                'sort_order' => 8,
            ],
            [
                'name' => 'DASSM - Disciplined Agile Senior Scrum Master',
                'name_ar' => 'DASSM - سكروم ماستر رشيق منضبط كبير',
                'description' => 'Advanced certification for senior scrum masters',
                'description_ar' => 'شهادة متقدمة لسكروم ماسترز الكبار',
                'code' => 'DASSM',
                'icon' => 'fas fa-user-tie',
                'color' => '#ec4899',
                'sort_order' => 9,
            ],
            [
                'name' => 'PMI-SP - Scheduling Professional',
                'name_ar' => 'PMI-SP - محترف الجدولة',
                'description' => 'Specialized certification in project scheduling',
                'description_ar' => 'شهادة متخصصة في جدولة المشاريع',
                'code' => 'PMI-SP',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#14b8a6',
                'sort_order' => 10,
            ],
        ];

        foreach ($certificates as $certificate) {
            Certificate::create($certificate);
        }
    }
} 