<?php
namespace BlackBoxCode\Pando\Bundle\ContentBundle\Service;

use BlackBoxCode\Pando\Bundle\ContentBundle\Document\FormDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Document\PageDocument;
use BlackBoxCode\Pando\Bundle\ContentBundle\Exception\Service\NoFormPageException;

class ForwardResolverService
{
    /**
     * Finds the success or failure page from the given Page/Form
     *
     * @param PageDocument $page
     * @param FormDocument $form
     * @param bool $isValid
     *
     * @throws NoFormPageException if no FormPage is found
     * @return PageDocument
     */
    public function resolve(PageDocument $page, FormDocument $form, $isValid)
    {
        if ($formPage = $page->getFormPages()->filter(
            function($formPage) use($form) {
                return $formPage->getForm() === $form;
            }
        )->first()) {
            return $isValid ? $formPage->getSuccessPage() : $formPage->getFailurePage();
        }

        throw new NoFormPageException('');
    }
}
