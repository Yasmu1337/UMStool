/*
 *	UMS Tool - Uniform mark conversion tools
 *	Copyright (C) 2011  Philip Kent
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function runAjax(url, form, spinner, submit, output)
{
	var params = Form.serialize(form);
	new Ajax.Updater(output, url, {
		asynchronous:true,
		method: "post",
		parameters: params,
		onLoading: function() {
			$(output).hide();
			$(submit).hide();
			$(spinner).show();
		},
		onSuccess: function() {
			$(submit).show();
			$(spinner).hide();
			Effect.Appear(output, { duration: 0.5 });
		},
		onFailure: function() {
			alert('An internal error occured');
			$(submit).show();
			$(spinner).hide();
		}
	});
}