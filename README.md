open wsl
cd /mnt/c/projects/msl_api

./vendor/bin/sail root-shell
npm run dev
npm run build

php artisan queue:work  --rest=1 --tries=3 --timeout=300


Originally assigned keywords -> msl_tags
Corresponding MSL vocabulary keywords -> msl_original_keywords
MSL enriched keywords -> msl_enriched_keywords