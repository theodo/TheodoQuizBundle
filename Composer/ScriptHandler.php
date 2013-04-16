<?php

namespace Theodo\QuizBundle\Composer;

use Symfony\Component\Filesystem\Filesystem;
use Composer\Script\CommandEvent;

class ScriptHandler
{
    public static function symlinkTwitterBootstrapFiles(CommandEvent $event)
    {
        $fileSystem = new Filesystem();

        $options = self::getOptions($event);
        $vendorDir = $options['symfony-app-dir'].'/../vendor';
        $twitterBootstrapBaseDir = $vendorDir.'/twitter/bootstrap';
        $twitterBootstrapTargetBaseDir = $options['symfony-web-dir'].'/bundles/twitter/bootstrap';

        $dirsToSymlink = array(
            $twitterBootstrapBaseDir.'/docs/assets/css' => $twitterBootstrapTargetBaseDir.'/css',
            $twitterBootstrapBaseDir.'/img' => $twitterBootstrapTargetBaseDir.'/img'
        );

        foreach ($dirsToSymlink as $originalDir => $targetDir) {
            if ($fileSystem->exists($originalDir)) {
                $fileSystem->mirror($originalDir, $targetDir, null, array('override' => true, 'copy_on_windows' => true));
            }
        }
        $event->getIO()->write(sprintf("Twitter bootstrap css and img files copied in %s \n", $twitterBootstrapTargetBaseDir), false);

        return;
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
            'symfony-app-dir' => 'app',
            'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }
}
