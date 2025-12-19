<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'code' => 'gl',
                'title_ar' => 'نظام الحسابات العامة',
                'title_en' => 'General Ledger',
                'description_ar' => 'دليل حسابات مرن، قيود يومية، مراكز تكلفة، ميزان مراجعة وتقارير مالية تدعم العملات المتعددة.',
                'description_en' => 'Flexible chart of accounts, journals, cost centers and full financial statements.',
                'icon_class' => 'fa-calculator',
                'sort_order' => 10,
            ],
            [
                'code' => 'inventory',
                'title_ar' => 'نظام المخازن',
                'title_en' => 'Inventory',
                'description_ar' => 'تعريف المخازن والأصناف، وحدات قياس والباركود، حركات الصرف والإضافة والجرد وتنبيهات حد الطلب.',
                'description_en' => 'Multi-warehouse, items with units & barcodes, stock movements, stock-take and alerts.',
                'icon_class' => 'fa-archive',
                'sort_order' => 20,
            ],
            [
                'code' => 'sales',
                'title_ar' => 'نظام العملاء والمبيعات',
                'title_en' => 'Sales & Customers',
                'description_ar' => 'عروض أسعار، أوامر بيع، فواتير ضريبية، أعمار ديون، وربط كامل مع الفاتورة الإلكترونية.',
                'description_en' => 'Quotations, sales orders, tax invoices, receivables aging and e-invoicing integration.',
                'icon_class' => 'fa-handshake-o',
                'sort_order' => 30,
            ],
            [
                'code' => 'purchases',
                'title_ar' => 'نظام الموردين والمشتريات',
                'title_en' => 'Purchases & Vendors',
                'description_ar' => 'طلبات شراء، أوامر شراء، فواتير موردين، أعمار الدائنين وربط المخازن بالحسابات العامة.',
                'description_en' => 'Purchase requests, POs, supplier invoices, payables aging and stock & GL integration.',
                'icon_class' => 'fa-truck',
                'sort_order' => 40,
            ],
            [
                'code' => 'einvoice',
                'title_ar' => 'حل الفاتورة الإلكترونية',
                'title_en' => 'E-Invoicing Integration',
                'description_ar' => 'تكامل مع منظومات الفاتورة الإلكترونية في السعودية ومصر، توليد وإرسال الفواتير إلكترونيًا.',
                'description_en' => 'Integration with e-invoicing platforms (ETA / ZATCA) with validated electronic invoices.',
                'icon_class' => 'fa-file-text-o',
                'sort_order' => 50,
            ],
            [
                'code' => 'hr',
                'title_ar' => 'نظام الموارد البشرية والحضور والرواتب',
                'title_en' => 'HR, Attendance & Payroll',
                'description_ar' => 'ملفات الموظفين، الحضور والانصراف، الإجازات، شيت الرواتب، الضرائب والتأمينات.',
                'description_en' => 'Employees records, attendance, leave, payroll, tax & social insurance calculations.',
                'icon_class' => 'fa-users',
                'sort_order' => 60,
            ],
            [
                'code' => 'contracting',
                'title_ar' => 'نظام المقاولات (قيد التطوير)',
                'title_en' => 'Contracting (in progress)',
                'description_ar' => 'إدارة المشروعات، بنود الأعمال، المستخلصات، وتحليل تكلفة المشاريع وربطها بباقي الأنظمة.',
                'description_en' => 'Projects, BOQ, progress billing and project cost analysis across all modules.',
                'icon_class' => 'fa-building',
                'sort_order' => 70,
            ],
        ];

        foreach ($items as $item) {
            Service::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
