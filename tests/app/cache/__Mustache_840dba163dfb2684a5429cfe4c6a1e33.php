<?php

class __Mustache_840dba163dfb2684a5429cfe4c6a1e33 extends Mustache_Template
{
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . 'Test 1, ';
        $value = $this->resolveValue($context->find('first'), $context, $indent);
        $buffer .= htmlspecialchars($value, 2, 'UTF-8');

        return $buffer;
    }
}
