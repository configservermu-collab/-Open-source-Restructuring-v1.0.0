
/****** Object:  Table [dbo].[WEBENGINE_PLUGINS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_PLUGINS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [varchar](100) NOT NULL,
	[author] [varchar](50) NOT NULL,
	[version] [varchar](50) NOT NULL,
	[compatibility] [varchar](max) NOT NULL,
	[folder] [varchar](max) NOT NULL,
	[files] [varchar](max) NOT NULL,
	[status] [int] NOT NULL,
	[install_date] [varchar](50) NOT NULL,
	[installed_by] [varchar](50) NOT NULL
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_PLUGINS] ON 

INSERT [dbo].[WEBENGINE_PLUGINS] ([id], [name], [author], [version], [compatibility], [folder], [files], [status], [install_date], [installed_by]) VALUES (5, N'Rename Character', N'Lautaro', N'1.1.0', N'1.2.0|1.2.1|1.2.2|1.2.3|1.2.4|1.2.5|1.2.6|1.2.7|1.2.8|1.2.9', N'renamecharacter', N'loader.php', 1, N'1753806606', N'Config66')
INSERT [dbo].[WEBENGINE_PLUGINS] ([id], [name], [author], [version], [compatibility], [folder], [files], [status], [install_date], [installed_by]) VALUES (6, N'Ticket Support System', N'Lautaro', N'1.5.0', N'1.2.0|1.2.1|1.2.2|1.2.3|1.2.4|1.2.5|1.2.6|1.2.7|1.2.8|1.2.9', N'ticketsystem', N'ticketsystem.lang.php|ticketsystem.class.php|ticketsystem.functions.php', 1, N'1753806629', N'Config66')
INSERT [dbo].[WEBENGINE_PLUGINS] ([id], [name], [author], [version], [compatibility], [folder], [files], [status], [install_date], [installed_by]) VALUES (3, N'VIP', N'Lautaro', N'1.1.0', N'1.2.0|1.2.1|1.2.2|1.2.3|1.2.4|1.2.5|1.2.6|1.2.7|1.2.8|1.2.9', N'vip', N'loader.php', 1, N'1753366853', N'gnsmuhard')
INSERT [dbo].[WEBENGINE_PLUGINS] ([id], [name], [author], [version], [compatibility], [folder], [files], [status], [install_date], [installed_by]) VALUES (4, N'Exchange Resets', N'Lautaro', N'1.2.0', N'1.2.0|1.2.1|1.2.2|1.2.3|1.2.4|1.2.5|1.2.6|1.2.7|1.2.8|1.2.9', N'exchangeresets', N'loader.php', 1, N'1753366912', N'gnsmuhard')
INSERT [dbo].[WEBENGINE_PLUGINS] ([id], [name], [author], [version], [compatibility], [folder], [files], [status], [install_date], [installed_by]) VALUES (7, N'Ticket Support System', N'Lautaro', N'1.5.0', N'1.2.0|1.2.1|1.2.2|1.2.3|1.2.4|1.2.5|1.2.6|1.2.7|1.2.8|1.2.9', N'ticketsystem', N'ticketsystem.lang.php|ticketsystem.class.php|ticketsystem.functions.php', 1, N'1753806639', N'Config66')
SET IDENTITY_INSERT [dbo].[WEBENGINE_PLUGINS] OFF


