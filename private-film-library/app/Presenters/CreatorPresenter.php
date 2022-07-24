<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\CreatorManager;
use Nette;
use Nette\Application\UI\Form;


final class CreatorPresenter extends Nette\Application\UI\Presenter
{
    private CreatorManager $creatorManager;

	public function __construct(CreatorManager $creatorManager)
	{
		$this->creatorManager = $creatorManager;
	}

    protected function createComponentCreatorForm(): Form
    {
        $types = $this->creatorManager->getCreatorType();

    	$form = new Form;
		$form->getElementPrototype()->class('ajax');
    	$form->addText('first_name', 'First name:')
    		->setRequired();
    	$form->addText('last_name', 'Last name:')
    		->setRequired();
        $form->addText('date_of_birth', 'Date of birth:')
            ->setHtmlType('date')
    		->setRequired();
        $form->addText('country', 'Country:')
    		->setRequired();
        $form->addSelect('type_id', 'Type:', $types)
            ->setRequired()
            ->setPrompt('Pick a type');

    	$form->addSubmit('send', 'Save');
    	$form->onSuccess[] = [$this, 'creatorFormSucceeded'];

    	return $form;
    }

	public function creatorFormSucceeded(array $data): void
    {
        $creatorId = intval($this->getParameter('creatorId'));

        $this->creatorManager->updateCreator($data, $creatorId);
        $this->flashMessage('New creator was updated', 'success');


		if ($this->isAjax()) {
            $this->redrawControl('ajaxChange');
            $this->redrawControl('flashMessages');
        }
    }

    public function renderShow(int $creatorId): void
    {
        $creator = $this->creatorManager->getCreator($creatorId);
        $creator->date_of_birth = date_format($creator->date_of_birth, 'Y-m-d');
        $this->getComponent('creatorForm')->setDefaults($creator);
        
        if (!$creator) {
            $this->error('Page not found!');
        }

        $this->template->creator = $creator;
    }

    public function actionDelete(int $creatorId): void
    {
        $this->creatorManager->deleteCreator($creatorId);

        $this->flashMessage('Creator was deleted', 'success');
    	$this->redirect('Creators:default');
    }

}
