<?php

/**
* 
*/
abstract class sfThemeBaseTask extends sfDoctrineGenerateModuleTask
{
  public function ask($question, $style = 'QUESTION', $default = null)
  {
    if ($default !== null) 
    {
      switch (true) 
      {
        case $default === true:
          $text = 'true';
          break;

        case $default === false:
          $text = 'false';
          break;
        
        default:
          $text = $default;
      }
      $question = sprintf('%s [%s]:', $question, $text);
    }
    
    // Add colon to make it clear this is a PROMPT
    if ($question[strlen($question)-1] !== ':') 
    {
      $question .= ':';
    }

    return parent::ask($question, $style, $default);
  }
  
  public function getThemeDir($theme, $class)
  {
    $dirs = array_merge(
      array(sfConfig::get('sf_data_dir').'/generator/'.$class.'/'.$theme),            // project
      $this->configuration->getPluginSubPaths('/data/generator/'.$class.'/'.$theme)   // plugins
    );
    
    foreach ($dirs as $dir)
    {
      if (is_dir($dir))
      {
        return $dir;
      }
    }
  }
  
  public function bootstrapSymfony($app, $env, $debug = true)
  {
    $this->configuration = ProjectConfiguration::getApplicationConfiguration($app, $env, $debug);
    
    // Prevents from accidental re-bootstrapping!
    if (!sfContext::hasInstance()) 
    {
      $this->context = sfContext::createInstance($this->configuration);
    }
  }
  
  public function getEventDispatcher()
  {
    return $this->dispatcher;
  }
}