<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <!-- Header -->
            <div class="text-center space-y-4">
                <div class="flex items-center justify-center space-x-3 rtl:space-x-reverse">
                    <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">مرحباً بك في {{ $systemName }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">دليل الاستخدام الشامل - اتبع الخطوات لبدء استخدام النظام</p>
                    </div>
                </div>
            </div>

            <!-- Progress Steps -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 rtl:ml-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    خطوات إعداد النظام
                </h2>

                <div class="space-y-6">
                    <!-- Step 1 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إعداد الإعدادات الأساسية</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">قم بملء جميع الإعدادات المطلوبة للنظام</p>
                            
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <a href="{{ \App\Filament\Resources\CountryResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    الدول والمحافظات
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\CityResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    المدن
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\CategoryResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    التصنيفات
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\LevelsResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                    المستويات
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\PaymentMethodsResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    طرق الدفع
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\PaymentStatusResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    حالات الدفع
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\ExpenseItemsResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    عناصر المصروفات
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\CrmLeadsStatusResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    حالات العملاء المحتملين
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\CrmLeadSourceResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    مصادر العملاء المحتملين
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\CrmCommunicationTypeResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    أنواع التواصل
                                </a>
                                
                                <a href="{{ \App\Filament\Resources\RoleResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    الأدوار والصلاحيات
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">2</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إضافة المدربين</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">قم بإضافة المدربين الذين سيقومون بتدريس الكورسات</p>
                            
                            <div class="mt-3">
                                <a href="{{ \App\Filament\Resources\InstructorResource::getUrl() }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    إضافة مدرب جديد
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">3</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إضافة المتدربين</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">قم بإضافة المتدربين الذين سينضمون للكورسات</p>
                            
                            <div class="mt-3">
                                <a href="{{ \App\Filament\Resources\StudentsResource::getUrl() }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    إضافة متدرب جديد
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">4</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إعداد التصنيفات والكورسات</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">قم بإضافة تصنيفات الكورسات ثم إضافة الكورسات نفسها</p>
                            
                            <div class="mt-3 space-y-3">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">أولاً: إضافة التصنيفات</h4>
                                    <a href="{{ \App\Filament\Resources\CategoryResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        إدارة التصنيفات (مثل: البرمجة، المحاسبة، اللغات)
                                    </a>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ثانياً: إضافة الكورسات</h4>
                                    <a href="{{ \App\Filament\Resources\CoursesResource::getUrl() }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        إدارة الكورسات
                                    </a>
                                    <ul class="mt-2 list-disc list-inside text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>وصف الكورس وتفاصيله</li>
                                        <li>الجروبات الخاصة بالكورس</li>
                                        <li>إضافة المتدربين</li>
                                        <li>التكليفات</li>
                                        <li>المدفوعات</li>
                                        <li>الاختبارات</li>
                                        <li>الشكاوى</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">5</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إدارة مجموعات الكورسات</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">يمكنك إضافة مجموعات متعددة لكل كورس، وتحديد مدرب ومواعيد خاصة لكل مجموعة.</p>
                            
                            <div class="mt-3">
                                <a href="course-groups" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    إدارة مجموعات الكورسات
                                </a>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">يمكنك أيضًا إضافة المجموعات من داخل تفاصيل الكورس في تبويب "المجموعات".</p>
                            </div>
                        </div>
                    </div>




      
                    <!-- Step 6 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">6</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إضافة متدرب للكورس المشترك فيه</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">قم بإدارة العملاء المحتملين ومتابعة تحويلهم إلى متدربين</p>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                يمكنك ربط المتدربين بالكورس بطريقتين:
                                <br>
                                <span class="font-semibold">الطريقة الأولى:</span> من خلال تسجيل الاشتراكات (<span class="font-mono">subscription-payments</span>) مع تحديد طريقة الدفع (كاش أو تقسيط).
                                <br>
                                <span class="font-semibold">الطريقة الثانية:</span> من داخل تفاصيل الكورس نفسه، في تبويب "المتدربين" يمكنك إضافة متدرب بشكل مباشر.
                            </p>
                            <div class="mt-3">
                                <a href="{{ \App\Filament\Resources\SubscriptionPaymentResource::getUrl() }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                              تسجيل الاشتراكات
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">7</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إدارة العملاء المحتملين (CRM)</h3>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-2 mt-2 space-y-1">
                                <li>
                                    <span class="font-semibold">إضافة العملاء المحتملين:</span> إدخال بيانات مثل الاسم، الإيميل، الهاتف، الكورس، المصدر، الحالة، الموظف المسؤول، ...إلخ
                                    <a href="{{ \App\Filament\Resources\LeadsResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Leads</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تسجيل المتابعات:</span> الموظف المسؤول يقوم بتسجيل ملاحظات المتابعة مع العميل المحتمل
                                    <a href="{{ \App\Filament\Resources\FollowUpResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Follow-ups</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تحويل العميل المحتمل إلى متدرب فعلي:</span> من صفحة إجراءات العملاء المحتملين
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 8 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">8</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">تسجيل الحضور والغياب</h3>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-2 mt-2 space-y-1">
                                <li>
                                    <span class="font-semibold">الطريقة الأولى:</span> من خلال صفحة الحضور والغياب، اختر الكورس والمجموعة واليوم ثم سجل الحضور والغياب للمتدربين.
                                    <a href="{{ \App\Filament\Resources\AttendanceResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Attendances</a>
                                </li>
                                <li>
                                    <span class="font-semibold">الطريقة الثانية:</span> من داخل تفاصيل الكورس، في تبويب "الحضور والغياب" يمكنك تسجيل الحضور مباشرة.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 9 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">9</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إدارة المالية</h3>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-2 mt-2 space-y-1">
                                <li>
                                    <span class="font-semibold">دفع الأقساط:</span> عرض جميع الأقساط (المدفوعة وغير المدفوعة) مع إمكانية عرض الفاتورة الخاصة بالدفع.
                                    <a href="{{ \App\Filament\Resources\SubscriptionPaymentResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">installment-payments</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تقرير المصروفات:</span> إضافة جميع المصروفات (إيجارات، فواتير كهرباء، غاز، إنترنت، ...إلخ).
                                    <a href="{{ \App\Filament\Resources\ExpenseResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">expenses</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تقرير الإيرادات:</span> عمل تقرير بالإيرادات خلال فترة زمنية محددة.
                                    <a href="revenue-reports" class="ml-2 text-primary-600 dark:text-primary-400 underline">revenue-reports</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تقرير الأقساط المتأخرة:</span> بحث بكورس أو متدرب، عرض قائمة بالأقساط المطلوب دفعها، مع إمكانية إرسال رسائل واتساب.
                                    <a href="overdue-installments-report" class="ml-2 text-primary-600 dark:text-primary-400 underline">overdue-installments-report</a>
                                </li>
                                <li>
                                    <span class="font-semibold">تقرير الأرباح:</span> معرفة الأرباح التي تحققت لكل كورس.
                                </li>
                                <li>
                                    <span class="font-semibold">عمليات الدفع:</span> عرض كل العمليات المالية (دفع أو استرجاع).
                                    <a href="{{ \App\Filament\Resources\PaymentTransactionsResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">payment-transactions</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 10 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">10</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إدارة الكورسات</h3>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-2 mt-2 space-y-1">
                                <li><span class="font-semibold">تفاصيل الكورس:</span> عرض جميع بيانات الكورس.</li>
                                <li><span class="font-semibold">المجموعات:</span> تبويب خاص بإدارة مجموعات الكورس.</li>
                                <li><span class="font-semibold">المتدربين:</span> تبويب خاص بالمتدربين التابعين للكورس، مع إمكانية إضافة متدرب جديد أو عمل استرجاع للأموال.</li>
                                <li><span class="font-semibold">الحضور والغياب:</span> تسجيل ومتابعة حضور وغياب المتدربين للكورس.</li>
                                <li><span class="font-semibold">المواد والفيديوهات التعليمية:</span> تبويب خاص برفع المواد والفيديوهات الخاصة بالكورس.</li>
                                <li><span class="font-semibold">التكليفات والاختبارات:</span> إضافة التكليفات والاختبارات (العنوان، المواعيد، الدرجة، الملف، التفاصيل)، مع إمكانية وضع نتائج الاختبار للطلاب.</li>
                                <li><span class="font-semibold">الشكاوى:</span> إدارة الشكاوى الخاصة بالكورس، مع اختيار المتدرب وتفاصيل الشكوى.</li>
                                <li><span class="font-semibold">جدول مواعيد الكورس:</span> عرض وتنظيم مواعيد المحاضرات والجلسات.</li>
                                <li><span class="font-semibold">المدفوعات:</span> تبويب خاص بجميع المدفوعات، الأقساط، والعمليات المالية التي تمت على الكورس.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 11 -->
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-semibold">11</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">إدارة المتدربين</h3>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 mb-2 mt-2 space-y-1">
                                <li><span class="font-semibold">تفاصيل الطالب:</span> عرض جميع بيانات الطالب بشكل كامل.</li>
                                <li><span class="font-semibold">الكورسات:</span> تبويب خاص بجميع الكورسات التي اشترك فيها الطالب
                                    <a href="{{ \App\Filament\Resources\CoursesResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Courses</a>
                                </li>
                                <li><span class="font-semibold">المواعيد:</span> عرض وتنظيم مواعيد الطالب في الكورسات.</li>
                                <li><span class="font-semibold">الحضور:</span> متابعة حضور وغياب الطالب في الكورسات
                                    <a href="{{ \App\Filament\Resources\AttendanceResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Attendances</a>
                                </li>
                                <li><span class="font-semibold">المدفوعات:</span> عرض جميع المدفوعات الخاصة بالطالب
                                    <a href="{{ \App\Filament\Resources\SubscriptionPaymentResource::getUrl() }}" class="ml-2 text-primary-600 dark:text-primary-400 underline">Subscription Payments</a>
                                </li>
                                <li><span class="font-semibold">الاختبارات:</span> عرض نتائج الاختبارات والتكليفات الخاصة بالطالب
                                    <a href="exams" class="ml-2 text-primary-600 dark:text-primary-400 underline">Exams</a>
                                </li>
                            </ul>
                        </div>
                    </div>


                </div>
            </div>

 
        
        </div>
    </x-filament::section>
</x-filament-widgets::widget> 