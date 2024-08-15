\documentclass[10pt,a4paper]{article}
\usepackage[utf8]{inputenc}
\usepackage{amsmath}
\usepackage{amsfonts}
\usepackage{amssymb}
\usepackage{graphicx}
\usepackage[left=2cm,right=2cm,top=2cm,bottom=2cm]{geometry}
\usepackage{tcolorbox}
\usepackage{geometry}
\geometry{a4paper, margin=1in}

\begin{document}

% Ticket template
\begin{tcolorbox}[colback=white!95!gray, colframe=blue!50!black, width=\textwidth, sharp corners, boxrule=0.5mm, title=Event Ticket]
    \begin{center}
        %\includegraphics[width=0.3\textwidth]{event_logo.png} % Add your event logo here
        \vspace{0.5cm}

        \Large \textbf{Event Name:} \normalsize{ {{ $event->title }} } \\
        \Large \textbf{Date:} \normalsize{ {{ $event->date->format('d-m-Y') }} } \\
        \Large \textbf{Address:} \normalsize{ {{ $event->address }} }
    \end{center}

    \vspace{0.5cm}

    \begin{tabbing}
        \hspace{4cm} \= \hspace{6cm} \= \kill
        \textbf{Email:} \> {{ $ticket->email }} \\
        \textbf{Phone:} \> {{ $ticket->phone }} \\
        \textbf{Ticket Type:} \> {{ $ticket_type->name }} \\
        \textbf{Price:} \> {{ $ticket->price }} XOF\\
        \textbf{Ticket Key:} \> {{ $ticket->key }} \\
        \textbf{Status:} \> {{ $ticket->status }} \\
        \textbf{Order ID:} \> {{ $order->number }} \\
        \textbf{Created On:} \> {{ $ticket->created_on }}
    \end{tabbing}

    \vspace{0.5cm}

\end{tcolorbox}

\end{document}
