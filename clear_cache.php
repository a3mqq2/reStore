<?php

// تشغيل أوامر Laravel لتنظيف الـ cache
exec('cd /Users/aishaaltery/web/2025/ReStore && php artisan route:clear', $output1, $return1);
exec('cd /Users/aishaaltery/web/2025/ReStore && php artisan config:clear', $output2, $return2);
exec('cd /Users/aishaaltery/web/2025/ReStore && php artisan cache:clear', $output3, $return3);
exec('cd /Users/aishaaltery/web/2025/ReStore && php artisan view:clear', $output4, $return4);

echo "Route cache cleared: " . ($return1 === 0 ? "✓" : "✗") . "\n";
echo "Config cache cleared: " . ($return2 === 0 ? "✓" : "✗") . "\n";
echo "Application cache cleared: " . ($return3 === 0 ? "✓" : "✗") . "\n";
echo "View cache cleared: " . ($return4 === 0 ? "✓" : "✗") . "\n";

echo "\nDone! الـ cache تم تنظيفه بنجاح\n";
