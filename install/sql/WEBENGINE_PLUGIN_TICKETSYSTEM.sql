
/****** Object:  Table [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[ticket_subject] [varchar](50) NOT NULL,
	[ticket_author] [varchar](50) NOT NULL,
	[ticket_create_date] [varchar](50) NOT NULL,
	[ticket_last_reply_date] [varchar](50) NULL,
	[ticket_last_reply_user] [varchar](50) NULL,
	[ticket_status] [int] NOT NULL
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM] ON 

INSERT [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM] ([id], [ticket_subject], [ticket_author], [ticket_create_date], [ticket_last_reply_date], [ticket_last_reply_user], [ticket_status]) VALUES (1, N'TEST TICKET SOPORTE', N'Config', N'1750359118', NULL, NULL, 0)
INSERT [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM] ([id], [ticket_subject], [ticket_author], [ticket_create_date], [ticket_last_reply_date], [ticket_last_reply_user], [ticket_status]) VALUES (1, N'TEST TICKET SOPORTE', N'Config', N'1750359118', NULL, NULL, 0)
SET IDENTITY_INSERT [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM] OFF
ALTER TABLE [dbo].[WEBENGINE_PLUGIN_TICKETSYSTEM] ADD  CONSTRAINT [DF_WEBENGINE_PLUGIN_TICKETSYSTEM_ticket_status]  DEFAULT ((0)) FOR [ticket_status]


