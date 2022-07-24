<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\CreatorManager;
use Nette\Application\UI\Form;

final class CreatorsPresenter extends Nette\Application\UI\Presenter
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
        $this->creatorManager->saveCreator($data);
        $this->flashMessage('New creator was added', 'success');


		if ($this->isAjax()) {
            $this->redrawControl('ajaxChange');
            $this->redrawControl('flashMessages');
        }
    }

    public function renderDefault(): void
    {
		$this->template->creators = $this->creatorManager->getCreators();
    }
}
