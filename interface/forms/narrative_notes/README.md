# Narrative Notes Component

A new OpenEMR component that allows healthcare providers to add daily narrative notes for patients on the patient dashboard.

## Features

- **Daily Notes**: Add narrative notes that are specific to each day
- **Patient-Specific**: Notes are tied to individual patients
- **Multi-User Access**: Any user with appropriate permissions can view and edit notes
- **Historical Viewing**: View notes from previous dates using a calendar date picker
- **Dashboard Integration**: Appears as a card on the patient dashboard alongside Custom Vitals and General Readings

## Installation

1. **Run the Installation Script**:
   - Navigate to `interface/forms/narrative_notes/register_now.php`
   - Click "Install Narrative Notes Form"
   - This will create the database table and register the form

2. **Verify Installation**:
   - Run the test script: `interface/forms/narrative_notes/test_narrative_notes.php`
   - Check that all tests pass

3. **Access the Component**:
   - Go to any patient dashboard
   - Look for the "Narrative Notes" card (appears after Custom Vitals)

## Usage

### Adding Daily Notes
1. Navigate to a patient's dashboard
2. Find the "Narrative Notes" card
3. Enter your notes in the text area
4. Notes are automatically saved as you type (after 2 seconds of inactivity)
5. You can also click "Save Now" for immediate saving

### Viewing Historical Notes
1. Click "View Historical Notes" button
2. Select a date using the calendar picker
3. View notes from that specific date

## Technical Details

### Database Schema
- Table: `form_narrative_notes`
- Fields: id, uuid, date, pid, user, groupname, authorized, activity, note_content

### Files Created
- `interface/forms/narrative_notes/table.sql` - Database schema
- `interface/forms/narrative_notes/save_note.php` - AJAX save handler
- `interface/forms/narrative_notes/get_notes.php` - AJAX handler for retrieving notes
- `interface/forms/narrative_notes/register_now.php` - Installation script
- `interface/patient_file/summary/narrative_notes_fragment.php` - Dashboard fragment with auto-save
- `src/Common/Forms/FormNarrativeNotes.php` - Form model class

### Permissions
- Requires `patients|med` ACL permission
- Same permission level as Custom Vitals and General Readings

## Development

This component follows the same patterns as existing OpenEMR form components:
- Uses the same database structure as other forms
- Integrates with the patient dashboard card system
- Follows OpenEMR coding standards and security practices
- Includes CSRF protection and proper sanitization

## Troubleshooting

If the component doesn't appear:
1. Check that the installation script ran successfully
2. Verify the form is registered in the `registry` table
3. Ensure you have the correct ACL permissions
4. Check the browser console for JavaScript errors
5. Run the test script to verify all components are working

## Future Enhancements

Potential improvements could include:
- Rich text editing capabilities
- Note templates
- Export functionality
- Integration with other OpenEMR modules
- Mobile-responsive improvements
