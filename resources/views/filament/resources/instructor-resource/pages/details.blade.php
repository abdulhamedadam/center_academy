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
                        <img src="{{asset('build/flags/student.png')}}" alt="Profile image"
                             class="w-full h-full object-cover"/>
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 border-white transform translate-x-1/4"></div>
                </div>
            </div>


            <div class="flex-grow">

                <div class="flex justify-between items-start flex-wrap mb-2">
                    <!-- User -->
                    <div class="animate-slide-in " style="opacity: 0; animation-delay: 0.4s;">

                        <div class="flex items-center mb-2">
                            <a href="#"
                               class="text-gray-900 hover:text-blue-600 text-2xl font-bold mr-1 transition duration-300" style="color: blue">{{@$instructor->name}}</a>
                            <span style="color: blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 animate-pulse"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                        </div>

                        <div class="flex flex-wrap text-sm font-semibold gap-3">
                            <a href="mailto:{{@$instructor->email}}"
                               class="flex items-center text-gray-500 hover:text-blue-600 mr-5 mb-2 transition duration-300" style="color: #9333ea;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{@$instructor->email}}
                            </a>
                            <a href="https://wa.me/{{@$instructor->phone}}"
                               class="flex items-center text-gray-500 hover:text-blue-600 mr-5 mb-2 transition duration-300" style="color: #16a34a;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                {{@$instructor->phone}}
                            </a>
                            <a href="#"
                               class="flex items-center text-gray-500 hover:text-blue-600 mb-2 transition duration-300" style="color: #f97316;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{@$instructor->address1}}
                            </a>
                        </div>
                        <div class="flex flex-wrap text-sm font-semibold mb-4 gap-3">

                            <div class="flex items-center text-gray-500 mr-5 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{__('common.city')}}: {{@$instructor->Country->name}}-{{@$instructor->City->name}}
                            </div>


                        </div>

                    </div>



                    <div class="flex my-4 animate-slide-in" style="opacity: 0; animation-delay: 0.6s;">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded mr-3 transition duration-300 transform hover:scale-105">
                            Hire Me
                        </button>
                        <!-- Menu -->
                        <div class="relative">
                            <button
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-full transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>


                <div class="flex flex-wrap flex-stack">
                    <!-- Stats Wrapper -->
                    <div class="flex flex-col flex-grow pr-8 ">
                        <div class="flex flex-wrap gap-4">
                            <!-- Courses Card -->
                            <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-purple-300 hover:shadow-md transition duration-300">
                                <a class="block">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <div class="text-sm font-bold">{{ @$instructor->courses->count() ?? 0 }}</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.courses')}}</div>
                                </a>
                            </div>

                            <!-- Students Card -->
                            <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-red-300 hover:shadow-md transition duration-300">
                                <a class="block">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <div class="text-sm font-bold">0</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.students')}}</div>
                                </a>
                            </div>

                            <!-- Reviews Card -->
                            <div class="stat-card border border-gray-300 border-dashed rounded min-w-[125px] py-3 px-4 mr-6 mb-3 hover:border-purple-300 hover:shadow-md transition duration-300">
                                <a class="block">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        <div class="text-sm font-bold">0</div>
                                    </div>
                                    <div class="font-semibold text-sm text-gray-500">{{__('common.reviews')}}</div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
        </div>

        <div class="flex flex-wrap text-sm font-semibold mb-4 gap-3">
            <div class="flex items-center text-gray-500 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="break-words">
            {{@$instructor->bio}}
        </span>
            </div>
        </div>



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
