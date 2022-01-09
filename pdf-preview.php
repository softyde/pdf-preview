<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;

/**
 * Class PdfPreviewPlugin
 * @package Grav\Plugin
 */
class PdfPreviewPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
//        $this->grav['debugger']->addMessage('getSubsribedEvents called');

        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
     * Composer autoload
     *
     * @return ClassLoader
     */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        $this->grav['debugger']->addMessage('onPluginsInitialized called 2');

        // Enable the main events we are interested in
        $this->enable([
            'onAdminSave' => ['onAdminSave', 0],
            'onAdminAfterAddMedia' => ['onAdminAfterAddMedia', 0]
        ]);
    }
    
    private function isHarmful($filename): bool 
    {
        foreach (array('"', '\'', '\\', '/', '&', ':') as $char) 
        {
            if (strpos($filename, $char) !== false) 
            {
                return true;
            }        
        }
                      
        return false;
    }
    
    public function onAdminSave($event): void 
    {


        $this->grav['log']->info('LOG ----');

        $page = $event['object'];
//        $this->grav['log']->info(print_r($page, true));     
//        dump($page);

        $pagePath = $page->path();
        
        $this->grav['log']->info($page->path());     

        foreach ($page->media()->files() as $filename => $file) {
        
            if ($file->mime  !== 'application/pdf') continue;
            if ($this->isHarmful($filename)) continue;
                        
            $sourcePath = "{$pagePath}/{$filename}";
            $targetPath = "{$pagePath}/__preview.{$filename}.png";

            if (file_exists($targetPath) === true) 
            {
                $this->grav['log']->info("Skipping {$sourcePath}");
                continue;
            }

            $cmd = "/usr/bin/convert -thumbnail  \"200^>\" -background white -alpha remove -crop 200x200+0+0 \"{$sourcePath}\"[0] \"{$targetPath}\"";
            // $cmd .= ' > /dev/null &';

            $this->grav['log']->info('Processing ' . $sourcePath . " as pdf file to {$targetPath}");
            $result = exec($cmd);   
            $this->grav['log']->info("Done processing");
        }
        
        
        
    }
    
    public function onAdminAfterAddMedia($event): void 
    {
        
        $this->grav['log']->info(print_r($event, true));     
        dump($event);
        
        $this->grav['admin']->setMessage('Yeah', 'info');
    }
    
}
