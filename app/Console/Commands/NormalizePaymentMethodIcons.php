<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\PaymentMethod;

class NormalizePaymentMethodIcons extends Command
{
    protected $signature = 'tunivert:normalize-payment-icons {--dry-run : Do not write changes, only report}';
    protected $description = 'Normalize payment method icon paths to storage/payment_methods and ensure files are accessible';

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $this->info('Normalizing PaymentMethod icon_path values' . ($dry ? ' (dry run)':'') . '...');

        $count = 0; $fixed = 0; $missing = 0;
        $methods = PaymentMethod::query()->whereNotNull('icon_path')->get();
        foreach ($methods as $pm) {
            $count++;
            $orig = $pm->icon_path;
            $new = $orig;

            // Normalize leading slash
            if ($new && str_starts_with($new, '/')) {
                $new = ltrim($new, '/');
            }

            // Convert legacy 'public/...' to 'storage/...'
            if ($new && str_starts_with($new, 'public/')) {
                $new = 'storage/' . substr($new, 7);
            }

            // If stored as 'storage/payment_methods/...', ensure file exists in public disk
            $relative = $new;
            if ($relative && str_starts_with($relative, 'storage/')) {
                $relative = substr($relative, 8); // payment_methods/...
            }

            if ($relative) {
                $existsPublic = file_exists(public_path('storage/' . $relative));
                $existsDisk = Storage::disk('public')->exists($relative);
                if (!$existsPublic && $existsDisk) {
                    // File exists in storage/app/public but not visible from public/storage (symlink missing?)
                    // storage:link should fix it. We just report here.
                    $this->warn("Symlink likely missing for {$pm->id}: storage/app/public/{$relative} not reachable at public/storage/{$relative}");
                } elseif (!$existsPublic && !$existsDisk) {
                    $missing++;
                    $this->error("Missing file for method {$pm->id} ({$pm->key}): expected storage/payment_methods, path: {$new}");
                }
            }

            if ($new !== $orig) {
                $this->line("Fixing icon_path for {$pm->key}: '{$orig}' => '{$new}'");
                if (!$dry) {
                    $pm->icon_path = $new;
                    $pm->save();
                    $fixed++;
                }
            }
        }

        $this->info("Processed {$count} methods. Fixed {$fixed}. Missing files: {$missing}.");
        $this->info('Ensure you have run: php artisan storage:link');
        return Command::SUCCESS;
    }
}
