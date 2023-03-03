import PySimpleGUI as sg
from fpdf import FPDF
import os
import tempfile

class PDF(FPDF):
    def __init__(self):
        # Call parent constructor
        FPDF.__init__(self)

        # Initialize data storage
        self.data = []

        # Set margins
        self.set_margins(25, 25)

        # Set font and size
        self.set_font('Arial', 'B', 16)

    def header(self):
        # Select Arial bold 15
        self.set_font('Arial', 'B', 30)
        # Move to the right
        self.cell(80)
        # Framed title
        self.cell(10, 10, 'Kontoinformasjon', 0, 0, 'C')
        # Line break
        self.ln(20)

    def add_data(self, username, password, account_type):
        # Add data to the storage
        self.data.append((username, password, account_type))

    def generate_pdf(self):
        # Add a new page
        self.add_page()



        # Set the x position for the logo
        logo_x = 10

        # Set the x position for the data
        data_x = 50

        # Print the data and add the appropriate logo
        for username, password, account_type in self.data:
            if account_type == 'Google':
                self.image('images/google_logo.png', logo_x, self.get_y(), 30)
            elif account_type == 'Microsoft':
                self.image('images/microsoft_logo.png', logo_x, self.get_y(), 30)
            elif account_type == 'Elkjøp Cloud':
                self.image('images/elkjopcloud_logo.png', logo_x, self.get_y(), 30)
            elif account_type == 'McAfee':
                self.image('images/mcafee_logo.png', logo_x, self.get_y(), 30)
            elif account_type == 'Other':
                self.image('images/other_logo.png', logo_x, self.get_y(), 30)

            self.set_xy(data_x, self.get_y())
            self.cell(0, 10, f'Brukernavn: {username}', 0, 1)
            self.set_xy(data_x, self.get_y())
            self.cell(0, 10, f'Passord: {password}', 0, 1)
            self.set_xy(data_x, self.get_y())
            self.cell(0, 10, f'Konto: {account_type}', 0, 1)
            self.ln()

        # Add the image at the bottom of the page
        self.image('images/elkjop.png', 10, 265, 50)
        self.image('images/image.png', 150, 265, 50)


# Create the layout for the GUI
layout = [
    [sg.Text('Username:', size=(15, 1)), sg.Input()],
    [sg.Text('Password:', size=(15, 1)), sg.Input(password_char='*')],
    [sg.Text('Account Type:', size=(15, 1)), sg.Combo(['Google', 'Microsoft', 'Elkjøp Cloud', 'McAfee', 'Other'], size=(20, 1))],
    [sg.Button('Add'), sg.Button('Generate PDF')],
    [sg.Listbox(values=[], size=(40, 10), key='listbox')]
]

# Create the window
window = sg.Window('Support Center - Accounts', layout)

# Create an instance of the PDF class
pdf = PDF()

# Run the event loop
while True:
    # Get the event and values
    event, values = window.read()

    # Add data to the PDF and update the listbox
    if event == 'Add':
        username = values[0]
        password = values[1]
        account_type = values[2]
        pdf.add_data(username, password, account_type)
        window['listbox'].update(values=[*window['listbox'].Values, f'{username} | {account_type}'])

    # Generate the PDF and clear the listbox
    if event == 'Generate PDF':
        temp_pdf = tempfile.NamedTemporaryFile(suffix='.pdf', delete=False)
        pdf.generate_pdf()
        pdf.output(temp_pdf.name, 'F')
        os.startfile(temp_pdf.name)
        window['listbox'].update(values=[])

    # Break the loop if the window is closed
    if event == sg.WIN_CLOSED:
        break

# Close the window
window.close()
