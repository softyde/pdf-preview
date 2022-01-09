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
        $this->grav['debugger']->addMessage('PDF Preview initialized');

        // Enable the main events we are interested in
        $this->enable([
            'onAdminSave' => ['onAdminSave', 0],
            'onAdminAfterAddMedia' => ['onAdminAfterAddMedia', 0]
        ]);
    }

    /**
     * Checks if the passed filename is somehow foolish
     */     
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
    
    /**
     * Is called when a page is saved and does all the work.
     */ 
    public function onAdminSave($event): void 
    {
        $page = $event['object'];

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
            // Could run in background but leads to problems with page caching.
            // Processing in foreground might lead to problems with long running php scripts
            // $cmd .= ' > /dev/null &';

            $this->grav['log']->info('Processing ' . $sourcePath . " as pdf file to {$targetPath}");
            $result = exec($cmd);   
        }
    }
    
    public function onAdminAfterAddMedia($event): void 
    {
        // Might be a better place to implement the stuff from above        
    }
    
}
