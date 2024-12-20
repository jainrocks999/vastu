<?php

namespace Botble\Course\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Course\Contracts\Course as CourseContract;
use Botble\Course\CourseSupport;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseCategory;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Repositories\Eloquent\CourseCategoryRepository;
use Botble\Course\Repositories\Eloquent\CourseRepository;
use Botble\Course\Repositories\Interfaces\CourseCategoryInterface;
use Botble\Course\Repositories\Interfaces\CourseInterface;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\Setting\PanelSections\SettingOthersPanelSection;

class CourseServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(CourseCategoryInterface::class, function () {
            return new CourseCategoryRepository(new CourseCategory());
        });

        $this->app->bind(CourseInterface::class, function () {
            return new CourseRepository(new Course());
        });

        $this->app->singleton(CourseContract::class, CourseSupport::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/course')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->publishAssets();

        if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Course::class, [
                'title',
                'code',
                'description',
                'image',
                'video',
                'docs',
                'type',
                'price',
                'instructor',
                'start_date',
                'end_date',
                'language',
                'prerequisites',
                'enrollment_limit',
                'enrollment_type',
                'certification',
                'discounts',
                'tags',
                'keywords',
                'course_category_id',
                'status',
            ]);

            LanguageAdvancedManager::registerModule(CourseCategory::class, [
                'name',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('course_schema_config_wrapper');

            foreach (config('plugins.course.general.schema_supported', []) as $item) {
                $translatableColumns = array_merge(LanguageAdvancedManager::getTranslatableColumns($item), ['course_schema_config', 'course_ids']);

                LanguageAdvancedManager::registerModule($item, $translatableColumns);
            }
        }

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course')
                        ->priority(1)
                        ->name('Courses')
                        ->icon('ti ti-book')
                        ->route('course.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course-list')
                        ->priority(0)
                        ->parentId('cms-plugins-course')
                        ->name('Manage Courses')
                        ->icon('ti ti-book')
                        ->route('course.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course-category')
                        ->priority(10)
                        ->parentId('cms-plugins-course')
                        ->name('plugins/course::course-category.name')
                        ->icon('ti ti-archive')
                        ->route('course_category.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course-discount.name')
                        ->priority(10)
                        ->parentId('cms-plugins-course')
                        ->name('Course Discount')
                        ->icon('ti ti-file-text')
                        ->route('course_discount.index')
                )
                // ->registerItem(
                //     DashboardMenuItem::make()
                //         ->id('cms-plugins-course-order.name')
                //         ->priority(10)
                //         ->parentId('cms-plugins-course')
                //         ->name('Course Orders')
                //         ->icon('ti ti-file-text')
                //         ->route('course_order.index')
                // )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course-review')
                        ->priority(10)
                        ->parentId('cms-plugins-course')
                        ->name('plugins/course::course-reviews.name')
                        ->icon('ti ti-star')
                        ->route('course_review.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-course-trainer.title')
                        ->priority(4) 
                        ->parentId('cms-plugins-course')
                        ->name('Trainer')
                        ->icon('ti ti-star')
                        ->route('trainer.index')
                );
        });

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('courses')
                    ->setTitle(trans('plugins/course::course.settings.title'))
                    ->withIcon('ti ti-book')
                    ->withPriority(420)
                    ->withDescription(trans('plugins/course::course.settings.description'))
                    ->withRoute('courses.settings')
            );
        });

        $this->app->register(HookServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }
}
