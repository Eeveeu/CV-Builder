<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Печать CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap&subset=cyrillic" rel="stylesheet">
    <style>
        /* For Dompdf use DejaVu Sans (supports Cyrillic) as primary; browser will use Inter if available */
        body { font-family: 'DejaVu Sans', 'Inter', Arial, Helvetica, sans-serif; color: #222; margin: 20px; }
        .paper { max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .name { font-size: 28px; font-weight: 700; }
        .contact { font-size: 14px; color: #555; margin-bottom: 20px; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: 700; color: #333; margin-bottom: 8px; }
        .job { margin-bottom: 8px; }
        .company { font-weight: 600; color: #333; }
        .period { color: #666; font-size: 13px; }
        .description { margin-top: 6px; color: #333; }
        .skills { display:flex; gap:8px; flex-wrap:wrap; }
        .skill { background:#333;color:#fff;padding:6px 10px;border-radius:12px;font-size:13px }
        @media print { body { margin:0 } .paper { box-shadow:none; } }
    </style>
</head>
<body>
    <div class="paper">
        <div class="header">
            <div class="name"><?php echo e($cv['personal']['name'] ?? 'Имя не указано'); ?></div>
            <div class="contact">📧 <?php echo e($cv['personal']['email'] ?? ''); ?> • 📱 <?php echo e($cv['personal']['phone'] ?? ''); ?> • 📍 <?php echo e($cv['personal']['city'] ?? ''); ?></div>
        </div>
        <?php if(!empty($cv['summary'])): ?>
            <div style="margin-bottom:12px; font-style:italic; color:#444"><?php echo e($cv['summary']); ?></div>
        <?php endif; ?>

        <?php if(!empty($cv['experience'])): ?>
            <div class="section">
                <div class="section-title">Опыт работы</div>
                <?php $__currentLoopData = $cv['experience']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="job">
                        <div class="company"><?php echo e($exp['company'] ?? ''); ?> — <strong><?php echo e($exp['role'] ?? $exp['position'] ?? ''); ?></strong></div>
                        <div class="period"><?php echo e($exp['period'] ?? ''); ?></div>
                        <?php if(!empty($exp['description'])): ?>
                            <div class="description"><?php echo e($exp['description']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($cv['education'])): ?>
            <div class="section">
                <div class="section-title">Образование</div>
                <?php $__currentLoopData = $cv['education']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $edu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="job">
                        <div class="company"><?php echo e($edu['school'] ?? $edu['institution'] ?? ''); ?> — <strong><?php echo e($edu['degree'] ?? $edu['specialization'] ?? ''); ?></strong></div>
                        <div class="period"><?php echo e($edu['year'] ?? ''); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <?php if(!empty($cv['skills'])): ?>
            <div class="section">
                <div class="section-title">Навыки</div>
                <div class="skills">
                    <?php $__currentLoopData = $cv['skills']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(is_array($skill)): ?>
                            <div class="skill"><?php echo e($skill['name'] ?? 'Навык'); ?></div>
                        <?php else: ?>
                            <div class="skill"><?php echo e($skill); ?></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($cv['languages'])): ?>
            <div class="section">
                <div class="section-title">Языки</div>
                <div><?php echo e(implode(', ', (array) $cv['languages'])); ?></div>
            </div>
        <?php endif; ?>

        <?php if(!empty($cv['links'])): ?>
            <div class="section">
                <div class="section-title">Ссылки</div>
                <div>
                    <?php $__currentLoopData = (array) $cv['links']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($ln); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto open print dialog for convenience (user can choose Save as PDF)
        window.addEventListener('load', function(){
            setTimeout(function(){ window.print(); }, 300);
        });
    </script>
</body>
</html>
<?php /**PATH C:\OSPanel\home\cv-builder\resources\views/cv/print.blade.php ENDPATH**/ ?>