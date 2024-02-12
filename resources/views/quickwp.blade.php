<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>QuickWP</title>

        <style>
            html, body {
                margin: 0;
                padding: 0;
            }

            #wp {
                width: 100%;
                height: 100vh;
                border: none;
            }
        </style>
    </head>
    <body>
    <iframe id="wp"></iframe>

    <script type="module">
        import { startPlaygroundWeb } from "{{config('services.quickwp.playground')}}/client/index.js";

        const client = await startPlaygroundWeb({
            iframe: document.getElementById('wp'),
            remoteUrl: `{{config('services.quickwp.playground')}}/remote.html`,
            blueprint: {
                landingPage: '/wp-admin/site-editor.php?quickwp=true&canvas=edit',
                phpExtensionBundles: [ 'kitchen-sink' ],
                constants: {
                    QUICKWP_APP_API: '{{config('services.quickwp.key')}}',
                    QUICKWP_APP_GUIDED_MODE: '{{config('services.quickwp.guided')}}',
                },
                features: {
                    networking: true
                },
                steps: [
                    {
                        step: 'login'
                    },
                    {
                        step: 'installPlugin',
                        pluginZipFile: {
                            resource: 'url',
                            url: '{{asset('wp/plugins/quickwp.zip')}}'
                        },
                        options: {
                            activate: true
                        }
                    },
                    {
                        step: 'installTheme',
                        themeZipFile: {
                            resource: 'url',
                            url: '{{asset('wp/themes/neve-fse.zip')}}'
                        },
                        options: {
                            activate: true
                        }
                    }
                ],
            },
        });

        await client.isReady();
    </script>
    </body>
</html>
