<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes progressFill {
        from {
            width: 0%;
        }
        to {
            width: 50%;
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .animate-slide-in {
        animation: slideIn 0.5s ease-out forwards;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    .animate-progress {
        animation: progressFill 1.5s ease-out forwards;
        animation-delay: 0.8s;
    }

    .tab-animate {
        position: relative;
        transition: all 0.3s ease;
    }

    .tab-animate:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 0;
        background-color: currentColor;
        transition: width 0.3s ease;
    }

    .tab-animate:hover:after {
        width: 100%;
    }

    .active-tab:after {
        width: 100%;
    }

    .stat-card {
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
    }

    .stat-card:nth-child(1) {
        animation-delay: 0.3s;
    }

    .stat-card:nth-child(2) {
        animation-delay: 0.5s;
    }

    .stat-card:nth-child(3) {
        animation-delay: 0.7s;
    }
    .text-blue-500 { color: #3b82f6; }
    .text-purple-500 { color: #8b5cf6; }
    .text-green-500 { color: #10b981; }
    .text-yellow-500 { color: #f59e0b; }
    .text-red-500 { color: #ef4444; }

    .stat-card:hover .text-blue-500 { color: #2563eb; }
    .stat-card:hover .text-purple-500 { color: #7c3aed; }
    .stat-card:hover .text-green-500 { color: #059669; }
    .stat-card:hover .text-yellow-500 { color: #d97706; }
    .stat-card:hover .text-red-500 { color: #dc2626; }
</style>
<x-filament::card>
    <div class=" overflow-hidden animate-fade-in">

            <div class="flex flex-wrap md:flex-nowrap gap-3">

                <div class="mr-7 mb-4 relative animate-slide-in" style="opacity: 0; animation-delay: 0.2s;">
                    <div class="relative">
                        <div class="shadow-lg" style="width: 200px; height: 200px; border: 4px solid #e5e7eb;">
                            <img src="{{asset('build/flags/course.png')}}" alt="Profile image"
                                 class="w-full h-full object-cover"/>
                        </div>
                        <div
                            class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 border-white transform translate-x-1/4"></div>
                    </div>
                </div>


                <div class="flex-grow">
                    <!-- Title Section -->
                    <div class="flex justify-between items-start flex-wrap mb-2">
                        <!-- User -->
                        <div class="animate-slide-in " style="opacity: 0; animation-delay: 0.4s;">

                            <div class="flex items-center mb-2">
                                <a href="#"
                                   class="text-gray-900 hover:text-blue-600 text-2xl font-bold mr-1 transition duration-300" style="color: blue">{{$course->name}}</a>
                                <span style="color: blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-pulse"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                            </div>

                            <div class="flex flex-wrap text-sm font-semibold  gap-3">
                                <a href="#"
                                   class="flex items-center text-gray-500 hover:text-blue-600 mr-5 mb-2 transition duration-300" style="color: #9333ea;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                    {{optional($course->Category)->name}}
                                </a>
                                <a href="#"
                                   class="flex items-center text-gray-500 hover:text-blue-600 mr-5 mb-2 transition duration-300" style=" color: #16a34a;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{optional($course->Instructor)->name}}
                                </a>
                                <a href="#"
                                   class="flex items-center text-gray-500 hover:text-blue-600 mb-2 transition duration-300" style=" color: #f97316;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    اون لاين
                                </a>
                            </div>
                            <div class="flex flex-wrap text-sm font-semibold mb-4 gap-3">
                                <!-- Start Date -->
                                <div class="flex items-center text-gray-500 mr-5 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{__('common.duration')}}: {{($course->duration)}}
                                </div>
                            </div>



                        </div>



                        <div class="flex my-4 animate-slide-in" style="opacity: 0; animation-delay: 0.6s;">
                            <button
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded mr-3 transition duration-300 transform hover:scale-105">
                                Hire Me
                            </button>
                            @if($course->status == 1)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 animate-fade-in ml-2">
                                    <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="10"/>
                                    </svg>
                                    {{ __('common.active') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 animate-fade-in ml-2">
                                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="10"/>
                                    </svg>
                                    {{ __('common.inactive') }}
                                </span>
                            @endif
                            <!-- Menu -->
        
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex flex-wrap flex-stack">
                        <!-- Stats Wrapper -->
                        <div class="flex flex-col flex-grow pr-8 ">
                            <div class="flex flex-wrap gap-4">

                                <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-blue-300 hover:shadow-md transition duration-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm font-bold">{{$course->duration.' '.__('common.months')}}</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.duration')}}</div>
                                </div>

                                <!-- Students Number Card -->
                                <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-blue-300 hover:shadow-md transition duration-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <div class="text-sm font-bold" id="students-counter">{{$course->CourseStudents->count()}}</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.students_number')}}</div>
                                </div>

                                <!-- Price Card -->
                                <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-blue-300 hover:shadow-md transition duration-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div class="text-sm font-bold" id="success-counter">{{$course->price}}</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.price')}}</div>
                                </div>

                                <!-- Discount Card -->
                                <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-blue-300 hover:shadow-md transition duration-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                        </svg>
                                        <div class="text-sm font-bold" id="success-counter">
                                            {{$course->discount}}
                                            @if($course->discount_type === 'p')
                                                %
                                            @elseif($course->discount_type === 'v')
                                                {{-- Display currency symbol or text as needed --}}
                                                (fixed)
                                            @endif
                                        </div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">
                                        {{__('common.discount')}}
                                        <span class="text-xs">
                ({{$course->discount_type ?? 'none'}})
            </span>
                                    </div>
                                </div>

                                <!-- Total Price Card -->
                                <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-blue-300 hover:shadow-md transition duration-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        <div class="text-sm font-bold" id="success-counter">{{$course->total_price}}</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.total_price')}}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <ul class="flex border-b border-gray-200 mt-6 gap-4">
                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1s;">
                    <a class="tab-animate @if($tap=='groups') active-tab @endif inline-block py-4 text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.groups', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{__('common.groups')}}
                    </a>
                </li>

                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1s;">
                    <a class="tab-animate @if($tap=='students') active-tab @endif inline-block py-4 text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.students', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{__('common.students')}}
                    </a>
                </li>
                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.1s;">
                    <a class="tab-animate @if($tap=='materials') active-tab @endif inline-block py-4 text-gray-600  font-bold" href="{{ route('filament.admin.resources.courses.materials', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        {{__('common.materials')}}
                    </a>
                </li>

                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.4s;">
                    <a class="tab-animate @if($tap=='schedules') active-tab @endif  inline-block py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.schedules', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{__('common.schedules')}}
                    </a>
                </li>
                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.3s;">
                    <a class="tab-animate inline-block @if($tap=='attendance') active-tab @endif py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.attendance', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        {{__('common.attendances')}}
                    </a>
                </li>



                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.5s;">
                    <a class="tab-animate inline-block @if($tap=='payments') active-tab @endif py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.payments', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{__('common.payments')}}
                    </a>
                </li>
                <!-- New Tabs -->
                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.6s;">
                    <a class="tab-animate inline-block @if($tap=='tests') active-tab @endif py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.tests', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{__('common.exams_quizzes')}}
                    </a>
                </li>
                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.7s;">
                    <a class="tab-animate inline-block @if($tap=='assignments') active-tab @endif py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.assignments', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{__('common.assignments')}}
                    </a>
                </li>

                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.2s;">
                    <a class="tab-animate inline-block @if($tap=='complaints') active-tab @endif py-4 text-gray-500 hover:text-blue-600 font-bold" href="{{ route('filament.admin.resources.courses.complaints', ['record' => $record]) }}">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        {{__('common.complaints')}}
                    </a>
                </li>


                <li class="mr-10 animate-fade-in" style="opacity: 0; animation-delay: 1.9s;">
                    <a class="tab-animate inline-block py-4 text-gray-500 hover:text-blue-600 font-bold" href="#announcements">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        {{__('common.announcements')}}
                    </a>
                </li>
            </ul>

    </div>
</x-filament::card>

<script>

    function animateCounter(elementId, targetValue, prefix = '') {
        let currentValue = 0;
        const element = document.getElementById(elementId);
        const duration = 1500; // Animation duration in ms
        const steps = 60; // Number of steps
        const stepValue = targetValue / steps;
        const stepTime = duration / steps;

        // Start animation after a delay
        setTimeout(() => {
            const interval = setInterval(() => {
                currentValue += stepValue;
                if (currentValue >= targetValue) {
                    currentValue = targetValue;
                    clearInterval(interval);
                }
                element.textContent = `${prefix}${Math.round(currentValue)}`;
            }, stepTime);
        }, 1000); // 1 second delay before starting
    }

    // Initialize counters
    document.addEventListener('DOMContentLoaded', () => {
        animateCounter('earnings-counter', 4500, '$');
        animateCounter('projects-counter', 80);
        animateCounter('success-counter', 60, '');

        // Add % sign after success counter finishes
        setTimeout(() => {
            const successCounter = document.getElementById('success-counter');
            successCounter.textContent = successCounter.textContent;
        }, 2600);

        // Tab animation
        const tabs = document.querySelectorAll('.tab-animate');

    });
</script>
