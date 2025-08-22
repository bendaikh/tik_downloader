<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title ?? 'Installer'}}</title>
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;500;600;700;900&display=swap"
          as="style">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body, html {
            background: linear-gradient(145deg, hsl(331, 93%, 48%), hsl(25, 88%, 53%));
            min-height: 100vh;
            font-family: var(--font-family);
            font-size: 1rem;
        }

        *, *:before, *:after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --font-family: 'Nunito', sans-serif;

            --color--05: hsla(0, 0%, 0%, .05);
            --color--10: hsla(0, 0%, 0%, .1);
            --color--20: hsla(0, 0%, 0%, .2);
            --color--30: hsla(0, 0%, 0%, .3);
            --color--40: hsla(0, 0%, 0%, .4);
            --color--50: hsla(0, 0%, 0%, .5);
            --color--55: hsla(0, 0%, 0%, .55);
            --color--85: hsla(0, 0%, 0%, .85);
            --color--75: hsla(0, 0%, 0%, .75);
            --color-primary: #4c4b9b;
            --color-primary--90: hsl(241, 35%, 45%, 0.9);
            --color-primary-light-3: #8281b9;
            --color-primary-light-5: #a6a5cd;
            --color-primary-light-7: #c9c9e1;
            --color-primary-light-8: #dbdbeb;
            --color-primary-light-9: #ededf5;
            --color-success: #2d8154;
            --color-error: #E54343;
            --color-error--10: hsl(0, 76%, 58%, 0.1);

            --card-shadow: 0 0 5px 2px rgba(0, 0, 0, .05);
            --header-height: 4rem;
        }

        body {
            padding: 2.5rem 0;
        }


        .container-wrapper {
            max-width: 750px;
            margin: 0 auto;
            --padding: 2.5rem;

        }

        .container {
            background: white;
            box-shadow: var(--card-shadow);
            border-radius: 0.5rem;
        }

        .alert {
            --alert-color: var(--color-primary);
            margin-bottom: 1.25rem;
            position: relative;
            border-radius: 0.5rem;
            color: var(--alert-color);
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 1rem;
            --font-size: 1.3rem;
            box-shadow: var(--card-shadow);
            isolation: isolate;
        }

        .alert.success {
            --alert-color: var(--color--75);
        }

        .alert.error {
            --alert-color: var(--color-error);
        }

        .alert:before {
            content: "";
            background: white;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: inherit;
            opacity: .5;
            z-index: -1;
        }

        .alert span {
            font-weight: 500;
            font-size: .875rem;
            display: inline-block;
            vertical-align: middle;
        }

        .container-content {
            padding: 2rem 0;
        }

        .container-footer {
            padding: 1rem;
            display: flex;
            align-content: center;
            justify-content: flex-end;
            border-bottom-left-radius: inherit;
            border-bottom-right-radius: inherit;
            gap: 1rem;
            background-color: rgba(0, 0, 0, .05);
        }

        .heading {
            margin-bottom: 1.25rem;
            text-align: center;
            padding: 0 var(--padding);
        }

        .heading h2, h3 {
            color: var(--color--75);
            font-weight: 700;
            line-height: 1.26em;
        }

        .heading h2 {
            font-size: 1.25rem;
        }

        .heading p {
            margin-top: 0.25rem;
            color: rgba(0, 0, 0, .5);
            font-weight: 400;
            font-size: .875rem;
            line-height: 1.26em;
        }

        .button {
            all: initial;
            box-sizing: inherit;
            font-family: inherit;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0.5rem 1rem;
            font-size: .875rem;
            font-weight: 700;
            border-radius: 6px;
            border: 1px solid var(--button-border-color, rgba(255, 255, 255, .7));
            background-color: var(--button-bg-color, rgba(255, 255, 255, .7));
            color: var(--button-text-color, var(--color--75));
        }

        .button:hover,
        .button:focus-visible {
            background-color: var(--button-hover-bg-color, rgba(255, 255, 255, .5));
            border-color: var(--button-hover-border-color, rgba(255, 255, 255, .7));
        }

        .button:focus-visible {
            outline: 2px solid var(--button-outline-color);
            outline-offset: 1px;
        }

        .button.is-primary {
            --button-text-color: white;
            --button-bg-color: var(--color-primary);
            --button-border-color: var(--color-primary);
            --button-outline-color: var(--color-primary-light-5);
            --button-hover-bg-color: var(--color-primary-light-3);
            --button-hover-border-color: var(--color-primary);
            --button-disabled-bg-color: var(--color-primary-light-5);
            --button-disabled-border-color: var(--color-primary-light-5);
        }

        .agreement {
            padding: 0.875rem 1.25rem;
            max-width: 95%;
            margin: 0 auto;
            border-radius: 0.25rem;
            background-color: rgba(0, 0, 0, .025);
            line-height: 1.3;
            font-size: 0.875rem;
        }

        .agreement h3 {
            margin-top: 0.75rem;
        }

        .agreement p {
            margin-top: 0.5rem;
        }

        .agreement ul {
            list-style-position: inside;
        }


        .status {
            width: 20px;
            height: 20px;
        }

        .status.passed {
            color: var(--color-success);
        }

        .status.failed {
            color: var(--color-error);
        }

        table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            font-size: 14px;
            line-height: 20px;
            color: var(--color--75);
        }

        table th {
            text-transform: uppercase;
            font-size: 12px;
            line-height: 20px;
            font-weight: 700;
            text-align: left;
            vertical-align: top;
            padding: 0.5rem 0.75rem;
            background-color: rgba(0, 0, 0, .05);
            border-bottom: 1px solid var(--color--10);
        }

        table td {
            padding: 0.625rem 0.75rem;
            border-bottom: 1px solid var(--color--10);
            vertical-align: top;
            text-align: left;
        }

        table tr.is-error td {
            background-color: var(--color-error--10);
        }

        table tr:last-child td {
            border-bottom-width: 0;
        }

        table th.status,
        table td.status {
            width: 50px;
            text-align: right;
        }

        .table {
            max-width: 95%;
            margin: 0 auto;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .requirements h3 {
            color: var(--color--55);
            background-color: rgba(0, 0, 0, 0.1);
            /*text-transform: uppercase;*/
            font-size: 0.75rem;
            margin: 0.875rem 0;
            font-weight: 700;
            padding: 0.5rem var(--padding);
            text-align: center;
        }

        form {
            padding: 1.5rem var(--padding) 0;
        }

        .form-element {
            display: flex;
            flex-direction: column;
            font-size: 14px;
            margin-bottom: 1rem;
        }

        .form-element label {
            margin-bottom: 12px;
            text-align: start;
            color: var(--color--55);
            display: inline-flex;
            align-items: flex-start;
            flex: 0 0 auto;
            font-weight: 700;
            font-size: inherit;
            line-height: 22px;
            padding: 0 12px 0 0;
            box-sizing: border-box;
        }

        .form-element.is-required label::after {
            content: "*";
            margin-left: 0.5ch;
        }

        .form-element input,
        .form-element select {
            --input-height: 40px;
        }

        .form-element select {
            background: url("data:image/svg+xml,<svg height='10px' width='10px' viewBox='0 0 16 16' fill='%23000000' xmlns='http://www.w3.org/2000/svg'><path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/></svg>") no-repeat calc(100% - 0.75rem) center;
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .form-element input,
        .form-element textarea,
        .form-element select {
            accent-color: var(--color-primary);
            height: var(--input-height, auto);
            border: 1px solid #dcdfe6;
            border-radius: 4px;
            padding: 0 12px;
            outline: 0;
            font-size: inherit;
            font-family: inherit;
            transition: border-color 200ms ease-in-out;
        }

        .form-element input[type=checkbox] {
            height: 16px;
            width: 16px;
        }


        .form-element textarea {
            padding: 12px;
            resize: vertical;
            min-height: 40px;
        }

        .form-element input:hover,
        .form-element textarea:hover {
            border-color: #c0c4cc;
        }

        .form-element input:focus,
        .form-element textarea:focus {
            border-color: var(--color-primary);
        }

        .form-element .checkbox {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .form-element .checkbox label {
            /*font-size: 1rem;*/
            margin-bottom: 0;
            user-select: none;
        }

        .form-element.is-error input,
        .form-element.is-error textarea {
            border-color: var(--color-error);
        }


        .form-element .error {
            padding-top: 4px;
            color: var(--color-error);
            font-size: 12px;
            line-height: 1;
            display: block;
        }

        .form-element .tip {
            padding-top: 4px;
            color: var(--color--55);
            font-size: 12px;
            line-height: 1;
            display: block;
        }

        .finish-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .finish-form .status-icon {
            font-size: 6.25rem;
            width: 1em;
            height: 1em;
            border-radius: 100%;
            flex-shrink: 0;
            background: linear-gradient(-145deg, hsl(331, 93%, 48%), hsl(25, 88%, 53%));
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .finish-form .status-icon svg {
            width: 0.5em;
            height: 0.5em;
            color: white;
        }

        .finish-form .heading {
            max-width: 500px;
        }

        .finish-form .button {
            margin-top: 1.25rem;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            padding: 0 var(--padding);
        }

        .progress-step {
            display: flex;
            align-items: center;
            color: var(--color--50);
            font-size: 0.875rem;
            font-weight: 600;
        }

        .progress-step.active {
            color: var(--color-primary);
        }

        .progress-step.completed {
            color: var(--color-success);
        }

        .progress-step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            background: var(--color--10);
            border: 2px solid var(--color--20);
            font-weight: 700;
        }

        .progress-step.active .progress-step-number {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }

        .progress-step.completed .progress-step-number {
            background: var(--color-success);
            border-color: var(--color-success);
            color: white;
        }

        .progress-step:not(:last-child)::after {
            content: '';
            width: 2rem;
            height: 2px;
            background: var(--color--20);
            margin: 0 0.5rem;
        }

        .progress-step.completed:not(:last-child)::after {
            background: var(--color-success);
        }
    </style>
</head>
<body>
<div class="container-wrapper">
    <x-installer.flash/>
    
    <div class="progress-steps">
        <div class="progress-step {{ request()->routeIs('installer.index') ? 'active' : (request()->routeIs('installer.*') ? 'completed' : '') }}">
            <div class="progress-step-number">1</div>
            <span>Introduction</span>
        </div>
        <div class="progress-step {{ request()->routeIs('installer.requirements') ? 'active' : (request()->routeIs('installer.database') || request()->routeIs('installer.admin') || request()->routeIs('installer.finish') ? 'completed' : '') }}">
            <div class="progress-step-number">2</div>
            <span>Requirements</span>
        </div>
        <div class="progress-step {{ request()->routeIs('installer.database') ? 'active' : (request()->routeIs('installer.admin') || request()->routeIs('installer.finish') ? 'completed' : '') }}">
            <div class="progress-step-number">3</div>
            <span>Database</span>
        </div>
        <div class="progress-step {{ request()->routeIs('installer.admin') ? 'active' : (request()->routeIs('installer.finish') ? 'completed' : '') }}">
            <div class="progress-step-number">4</div>
            <span>Admin</span>
        </div>
        <div class="progress-step {{ request()->routeIs('installer.finish') ? 'active' : '' }}">
            <div class="progress-step-number">5</div>
            <span>Finish</span>
        </div>
    </div>
    
    <div class="container">
        <div class="container-content">
            {{$slot}}
        </div>

        @if(isset($footer))
            <footer class="container-footer">
                {{$footer}}
            </footer>
        @endif
    </div>
</div>
</body>
</html>
