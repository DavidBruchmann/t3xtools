#!/bin/bash

ext="toolbox_utf8"
workingpath="/tmp"
svnpath="https://svn.typo3.org/TYPO3v4/Extensions/toolbox_utf8/trunk"


svn export $svnpath $workingpath/$ext


