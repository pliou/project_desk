<?php

namespace Ppl\ProjectDesk\Ajax;

use Ppl\ProjectDesk\Helper\DataHelper;

abstract class AbstractAjaxController
{
    public function __construct(
        protected readonly DataHelper $dataHelper,
    ) {}
}
