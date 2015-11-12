<?php

namespace StaticReview\Review\Bigcommerce\PHP;

use StaticReview\Commit\CommitMessageInterface;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class VarDumpReview extends AbstractReview
{
    protected function canReviewFile(FileInterface $file)
    {
        $extension = $file->getExtension();
        return ($extension === 'php');
    }

    protected function canReviewMessage(CommitMessageInterface $message) {
        return false;
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $file)
    {
        $cmd = sprintf('grep --fixed-strings --ignore-case --quiet "var_dump" %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();

        if ($process->isSuccessful()) {
            $message = 'A call to `var_dump()` was found';
            $reporter->error($message, $this, $file);
        }
    }
}