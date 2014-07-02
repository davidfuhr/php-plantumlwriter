<?php

interface OneInterface
{
}

interface TwoInterface extends OneInterface
{
}

class OneClass implements OneInterface
{
}

class TwoClass implements TwoInterface
{
}
