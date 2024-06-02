<?php

namespace ProviderMain\Error;

use ErrorException;
use Throwable;

class ErrorValue
{
    public $error = [];
    public static function Error(Throwable $error)
    {
        http_response_code(500);
        $ErrorArray = [
            "code" => $error,
            "message" => $error->getMessage(),
           
        ];
        $getfile = file_get_contents($error->getFile());
        $array = explode("\n",$getfile);
        echo "
        <div class='error'>
        <div class='errors'>
        <div class='error-head'>
        <p>".$error ."</p>
        </div>
        <div class='error-body'>
        <div class='media'>
        <div class='col-span-6'>
        ";
        foreach ($ErrorArray as $key => $value) {
        ?>
            <div>
                <ul>
                    <li>
                        <span class="">[<?= $key ?>] </span> =>
                        <span class="">[<?= $value ?>] </span>
                    </li>
                </ul>
            </div>
        <?php
        }
        echo "</div>
        <div class='col-span-6 bg-gray-400 w-full h-[450px] overflow-y-auto'>
        
        <div class='px-2 py-2'>
        <div class='text-center'>
            <p class='font-mono text-white'>{$error->getFile()}</p>
        </div>
        ";
        foreach ($array as $arrayKey => $arrayvalue) {
            ?>
            <div class="px-4 py-1 mb-4">
                <ul>
                    <li>
                        <?php
                            if($arrayKey+1===$error->getLine())
                            {
                                echo "<span class='text-indigo-950'>=></span>";
                            }
                        ?>
                        <span  class="<?= $arrayKey+1>=$error->getLine()?'text-red-200':' text-indigo-600' ?>" ><?= $arrayKey+1 ?> </span>
                        <span class="<?= $arrayKey+1>=$error->getLine()?'text-red-200 underline':' text-white font-mono' ?>"><?= $arrayvalue ?> </span>
                    </li>
                </ul>
            </div>
        <?php
        }
        echo "
        </div>
        </div>
        </div>
        ";
        echo "
        </div>
            <div class='error-footer'>
            <p>cmsspark.copyright2024@</p>
            </div>
            </div>
        </div>
        ";
    }
    public static function ErrorValue(string $title, array $error = []): void
    {

        http_response_code(500);
        echo "
        <div class='error overflow-hidden>
        <div class='error-head'>
        <p>$title</p>
        </div>
        <div class='error-body'>
        ";
        foreach ($error as $key => $value) {
        ?>
            <div>
                <ul>
                    <li>
                        <span class="">[<?= $key ?>] </span> =>
                        <span class="">[<?= $value ?>] </span>
                    </li>
                </ul>
            </div>
<?php
        }
        echo "
        </div>
            <div class='error-footer'>
            <p>cmsspark.copyright2024@</p>
            </div>
        </div>
        ";
    }
    public static function Page404(string $page)
    {
        http_response_code(404);
        echo '
        <div class="h-screen fixed bg-stone-400 top-0 w-screen">
        <div class="h-screen flex justify-center items-center">
            <div class="text-white font-bold text-[18px]">
                Page not found | ' . $page . '
            </div>
        </div>
        <div class="error-footer">
            <p>cmsspark.copyright2024@</p>
            </div>
    </div>
        ';
        die();
    }
}
