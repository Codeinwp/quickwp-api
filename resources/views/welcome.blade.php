<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>QuickWP</title>

        <!-- Styles -->
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>

    <body class="antialiased">
        <div class="flex flex-col justify-center min-h-screen bg-gradient selection:bg-red-500 selection:text-white">
            <div class="max-w-4xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <svg width="128" height="128" viewBox="0 0 115 115" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M57.5 93C77.6584 93 94 76.6584 94 56.5C94 36.3416 77.6584 20 57.5 20C37.3416 20 21 36.3416 21 56.5C21 76.6584 37.3416 93 57.5 93ZM57.5 73.9565C67.141 73.9565 74.9565 66.141 74.9565 56.5C74.9565 46.859 67.141 39.0435 57.5 39.0435C47.859 39.0435 40.0435 46.859 40.0435 56.5C40.0435 66.141 47.859 73.9565 57.5 73.9565Z" fill="white" />
                        <rect x="63.9414" y="86.8833" width="20.0392" height="14.5311" transform="rotate(-30 63.9414 86.8833)" fill="white" />
                    </svg>
                </div>

                <div class="mt-8 text-center flex flex-col gap-12 items-center">
                    <h1 class="mb-4 text-4xl semifont-bold leading-none text-white tracking-tight md:text-5xl lg:text-6xl">Create an AI-generated theme for Gutenberg in minutes</h1>
                    <h2 class="text-2xl text-white">QuickWP allows you to create a WordPress theme with styles, content, and images based on your input.</h2>
                    <a id="start" class="px-2 py-4 w-44 cursor-pointer text-base font-medium text-center text-white bg-primary rounded-lg hover:bg-primary/50 focus:ring-4 focus:outline-none focus:ring-blue-300">Get Started</a>
                </div>
            </div>

            <div class="flex justify-center mt-4 px-0 sm:items-center sm:justify-center">
                <div class="text-center text-xs text-white font-semibold uppercase">
                    By ThemeIsle
                </div>
            </div>

            <div id="email-modal" class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="false">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">

                            <div class="relative isolate overflow-hidden bg-gradient p-16">
                                <div class="mx-auto px-6 lg:px-8">
                                    <div>
                                        <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Get started with QuickWP</h2>
                                        <p class="mt-4 text-lg leading-8 text-gray-300">Your feedback will shape its future, tailoring it to fit your WordPress needs. Sign up, share your thoughts, and help us perfect QuickWP.</p>

                                        <div class="flex gap-3 mt-4">
                                            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Your Email" required>
                                            <a id="subscribe" class="w-44 flex items-center justify-center cursor-pointer text-base font-medium text-center text-white bg-primary rounded-lg hover:bg-primary/50 focus:ring-4 focus:outline-none focus:ring-blue-300">Get Started</a>
                                        </div>

                                        <div class="flex justify-center pt-4">
                                            <a href="start" class="text-gray-400 text-sm hover:text-white hover:underline">Continue without Email</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
